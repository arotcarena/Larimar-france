<?php
namespace App\Email\Fr\Security;

use App\Config\SiteConfig;
use App\Email\EmailFactory;
use App\Entity\User;
use Symfony\Component\Mime\Email;

class ConfirmationEmail extends EmailFactory
{
    public function send(User $user)
    {
        $link = SiteConfig::SITE_URL .
                $this->urlGenerator->generate('fr_security_emailConfirmation') .
                '?token='.$user->getId().'=='.$user->getConfirmationToken();

        $email = (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($user->getEmail())
            ->subject('Confirmez votre adresse email')
            ->text('Bienvenue sur Larimar France ! Veuillez suivre ce lien pour activer votre compte : '.$link)
            ->html($this->twig->render('fr/email/security/confirmation_email.html.twig', [
                'link' => $link
            ]));

        $this->sendEmail($email);
    }
}