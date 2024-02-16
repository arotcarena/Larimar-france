<?php

namespace App\Controller\En\Security;

use App\Config\EnTextConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/en/login', name: 'en_security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('en_home');
        }
        $errorMessage = null;
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if($error)
        {
            $errorMessage = $error->getCode() === 100 ? $error->getMessage(): EnTextConfig::ERROR_INVALID_CREDENTIALS;
        }
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('en/security/login.html.twig', ['last_username' => $lastUsername, 'errorMessage' => $errorMessage]);
    }

    //il ne peut pas y avoir 2 logout
    // #[Route(path: '/en/logout', name: 'security_logout')]
    // public function logout(): void
    // {
        
    // }
}
