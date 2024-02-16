<?php
namespace App\Email\En\Security;

use App\Config\SiteConfig;
use App\Email\EmailFactory;
use App\Entity\User;
use Symfony\Component\Mime\Email;

class ConfirmationEmail extends EmailFactory
{
    public function send(User $user)
    {
        $link = SiteConfig::SITE_URL .
                $this->urlGenerator->generate('en_security_emailConfirmation') .
                '?token='.$user->getId().'=='.$user->getConfirmationToken();

        $email = (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($user->getEmail())
            ->subject('Verify your email address')
            ->text('Welcome to Larimar France ! Please follow this link to activate your account : '.$link)
            ->html($this->twig->render('en/email/security/confirmation_email.html.twig', [
                'link' => $link
            ]));

        $this->sendEmail($email);
    }
}