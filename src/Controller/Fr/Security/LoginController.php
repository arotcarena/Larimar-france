<?php

namespace App\Controller\Fr\Security;

use App\Config\TextConfig;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/fr/connexion', name: 'fr_security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $errorMessage = null;
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        if($error)
        {
            $errorMessage = $error->getCode() === 100 ? $error->getMessage(): TextConfig::ERROR_INVALID_CREDENTIALS;
        }
        
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('fr/security/login.html.twig', ['last_username' => $lastUsername, 'errorMessage' => $errorMessage]);
    }

    #[Route(path: '/logout', name: 'security_logout')]
    public function logout(): void
    {

    }
}
