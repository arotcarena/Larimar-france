<?php
namespace App\Email\En\Security;

use App\Config\SiteConfig;
use App\Email\EmailFactory;
use Symfony\Component\Mime\Email;

class EmailChangeEmail extends EmailFactory
{
    public function send(string $email, string $code)
    {
        $email = (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($email)
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Email address change')
            ->text('To confirm your email address change, please enter the following code : ' .$code)
            ->html($this->twig->render('en/email/security/email_change_email.html.twig', [
                'code' => $code
            ]));
        $this->sendEmail($email);
    }
}