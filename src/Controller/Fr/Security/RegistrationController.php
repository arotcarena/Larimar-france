<?php

namespace App\Controller\Fr\Security;

use Exception;
use App\Config\TextConfig;
use App\Persister\UserPersister;
use App\Form\UserRegistrationType;
use App\Repository\UserRepository;
use App\Security\TokenVerificator;
use App\Form\DataModel\UserRegistration;
use Doctrine\ORM\EntityManagerInterface;
use App\Email\Fr\Security\ConfirmationEmail;
use App\Repository\PurchaseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private UserPersister $userPersister,
        private ConfirmationEmail $confirmationEmail,
        private EntityManagerInterface $em,
        private TokenVerificator $tokenVerificator,
        private UserRepository $userRepository,
        private TokenStorageInterface $tokenStorage,
        private PurchaseRepository $purchaseRepository
    )
    {

    }


    #[Route('/fr/créer-un-compte', name: 'fr_security_register')]
    public function register(Request $request): Response
    {
        
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        
        $userRegistration = new UserRegistration;
        $form = $this->createForm(UserRegistrationType::class, $userRegistration);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {
            $user = $this->userPersister->persist($userRegistration);
            $this->confirmationEmail->send($user);
            $this->addFlash('success', TextConfig::ALERT_REGISTER_SUCCESS);
            return $this->redirectToRoute('fr_security_login');
        }

        return $this->render('fr/security/register.html.twig', [
            'registrationForm' => $form->createView()
        ]);
    }

    #[Route('/fr/activer-mon-compte', name: 'fr_security_emailConfirmation')]
    public function emailConfirmation(Request $request): Response
    {
        $user = $this->tokenVerificator->resolveUser($request->query->get('token'), 'confirmationToken');
        if($user)
        {
            $user->setConfirmed(true)
                ->setConfirmationToken(null)
                ->setConfirmationTokenExpireAt(null)
                ;
            $this->em->flush();
            $this->addFlash('success', TextConfig::ALERT_CONFIRMATION_SUCCESS);
            return $this->redirectToRoute('fr_security_login');
        }
        $this->addFlash('danger', 'Le lien est invalide ou périmé');
        return $this->redirectToRoute('home');
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/fr/supprimer-mon-compte', name: 'fr_security_accountDelete', methods: ['POST', 'GET'])]
    public function accountDelete(Request $request): Response
    {
        if($this->purchaseRepository->hasPurchasesInProgress($this->getUser()))
        {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer votre compte tant que vous avez des commandes en cours. Veuillez attendre la finalisation de toutes vos commandes');
            return $this->redirectToRoute('fr_account_index');
        }
        if($request->getMethod() === 'POST')
        {
            $token = $request->request->get('token');
            if (!$this->isCsrfTokenValid('delete-user', $token)) 
            {
                throw new Exception('Le formulaire n\'a pas été soumis correctement');
            }
            /** @var User */
            $user = $this->getUser();
            //logout
            $this->tokenStorage->setToken(null);
            //suppression du user
            $this->em->remove($user);
            $this->em->flush();
            $this->addFlash('success', 'Votre compte a bien été supprimé !');
            //on supprime le target qui se met du fait qu'on est déconnecté et que ce controller est sous auth
            $request->getSession()->remove('_security.main.target_path');
            
            return $this->redirectToRoute('home');
        }
        return $this->render('fr/security/account_delete.html.twig');
    }
}
