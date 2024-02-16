<?php
namespace App\Email\Fr\Security;

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
            ->subject('Changement d\'adresse email')
            ->text('Pour confirmer votre changement d\'adresse email, veuillez entrer le code suivant : ' .$code)
            ->html($this->twig->render('fr/email/security/email_change_email.html.twig', [
                'code' => $code
            ]));
        $this->sendEmail($email);
    }
}