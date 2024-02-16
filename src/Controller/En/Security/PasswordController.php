<?php
namespace App\Controller\En\Security;

use Exception;
use App\Entity\User;
use App\Config\SiteConfig;
use App\Config\EnTextConfig;
use App\Form\NewPasswordType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Security\TokenVerificator;
use App\Form\DataModel\NewPassword;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use App\Email\En\Security\ResetPasswordEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PasswordController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $hasher,
        private TokenGeneratorInterface $tokenGenerator,
        private EntityManagerInterface $em,
        private ResetPasswordEmail $resetPasswordEmail,
        private TokenVerificator $tokenVerificator
    )
    {

    }

    #[Route('/en/forgotten-password', name: 'en_security_askResetPassword', methods: ['POST', 'GET'])]
    public function askResetPassword(Request $request)
    {
        $error = null;
        if($request->getMethod() === 'POST')
        {
            try
            {
                $email = $request->request->get('email');
                /** @var User */
                $user = $this->userRepository->findOneByEmail($email);
                if($user)
                {
                    $user->setResetPasswordToken($this->tokenGenerator->generateToken())
                        ->setResetPasswordTokenExpireAt(time() + SiteConfig::TOKEN_TIME_VALIDITY)
                        ;
                    $this->em->flush();
                    $this->resetPasswordEmail->send($user);
                    $this->addFlash('success', EnTextConfig::ALERT_RESET_PASSWORD);
                    return $this->redirectToRoute('en_security_login');
                }
                $error = $email === '' ? 'Vous devez entrer votre adresse email': 'Cette adresse email ne correspond à aucun compte';
            }
            catch(Exception $e)
            {
                $error = 'Vous devez entrer votre adresse email';
            }
        }
        return $this->render('en/security/ask_reset_password.html.twig', [
            'error' => $error
        ]);
    }

    #[Route('/en/reset-my-password', name: 'en_security_resetPassword')]
    public function resetPassword(Request $request): Response
    {
        /** vérification du token */
        if($request->getMethod() === 'GET')
        {
            $user = $this->tokenVerificator->resolveUser($request->query->get('token'), 'resetPasswordToken');
            if($user === null)
            {
                $this->addFlash('danger', 'Le lien est invalide ou périmé');
                return $this->redirectToRoute('en_home');
            }
        }
        
        /** soumission du nouveau mot de passe */
        $newPassword = new NewPassword;
        $form = $this->createForm(NewPasswordType::class, $newPassword);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $user = $this->tokenVerificator->resolveUser($request->request->get('token'), 'resetPasswordToken');
            $user
                ->setPassword(
                    $this->hasher->hashPassword($user, $newPassword->getPassword())
                )
                ->setResetPasswordToken(null)
                ->setResetPasswordTokenExpireAt(null)
                ;
            $this->em->flush();
            $this->addFlash('success', EnTextConfig::ALERT_RESET_PASSWORD_SUCCESS);
            return $this->redirectToRoute('en_security_login');
        }
        return $this->render('en/security/reset_password.html.twig', [
            'form' => $form->createView(),
            'reset_token' => $request->get('token')
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/en/change-my-password', name: 'en_security_changePassword')]
    public function changePassword(Request $request): Response 
    {
        $newPassword = new NewPassword;
        $form = $this->createForm(ChangePasswordType::class, $newPassword);
        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            /** @var User */
            $user = $this->getUser();
            $oldPassword = $form->get('oldPassword')->getData();
            if($oldPassword === null)
            {
                $form->get('oldPassword')->addError(new FormError('L\'ancien mot de passe est obligatoire'));
            }
            elseif(!$this->hasher->isPasswordValid($user, $oldPassword))
            {
                $form->get('oldPassword')->addError(new FormError('Mot de passe incorrect'));
            }

            if($form->isValid())
            {
                $user->setPassword(
                    $this->hasher->hashPassword($user, $newPassword->getPassword())
                );
                $this->em->flush();
                $this->addFlash('success', EnTextConfig::ALERT_RESET_PASSWORD_SUCCESS);
                return $this->redirectToRoute('en_home');
            }
        }

        return $this->render('en/security/change_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}