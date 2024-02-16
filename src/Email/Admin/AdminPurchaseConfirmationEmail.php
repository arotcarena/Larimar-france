<?php
namespace App\Email\Admin;

use App\Config\SiteConfig;
use App\Email\EmailFactory;
use App\Entity\Purchase;
use Symfony\Component\Mime\Email;

class AdminPurchaseConfirmationEmail extends EmailFactory
{
    public function send(Purchase $purchase)
    {
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        $email = (new Email())
                ->from(SiteConfig::EMAIL_NOREPLY)
                ->to(SiteConfig::EMAIL_ADMIN)
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Vous avez reçu une commande')
                ->text('Vous avez reçu une commande')
                ->html($this->twig->render('admin/email/purchase_confirmation.html.twig', [
                    'purchase' => $purchase,
                    'isAtHomeDelivery' => $isAtHomeDelivery
                ]));

        $this->sendEmail($email);
    }
}