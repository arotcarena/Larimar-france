<?php
namespace App\Email\En\Security;

use App\Config\SiteConfig;
use App\Email\EmailFactory;
use App\Entity\User;
use Symfony\Component\Mime\Email;

class ResetPasswordEmail extends EmailFactory
{
    public function send(User $user)
    {
        $link = SiteConfig::SITE_URL .
                $this->urlGenerator->generate('en_security_resetPassword') .
                '?token='.$user->getId().'=='.$user->getResetPasswordToken();

        $email = (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($user->getEmail())
            ->subject('Reset your password')
            ->text('You\'ve asked to reset your password. Please follow this link to create a new password : '.$link)
            ->html($this->twig->render('en/email/security/reset_password_email.html.twig', [
                'link' => $link
            ]));

        $this->sendEmail($email);
    }
}