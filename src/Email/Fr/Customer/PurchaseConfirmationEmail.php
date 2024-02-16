<?php
namespace App\Email\Fr\Customer;

use App\Config\SiteConfig;
use App\Email\EmailFactory;
use App\Entity\Purchase;
use App\Twig\Runtime\PriceFormaterExtensionRuntime;
use Symfony\Component\Mime\Email;

class PurchaseConfirmationEmail extends EmailFactory
{

    public function send(Purchase $purchase)
    {
        $priceFormater = new PriceFormaterExtensionRuntime;
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;


        $email = (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($purchase->getUser()->getEmail())
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Confirmation de commande')
            ->text('Merci pour votre commande ! Nous avons bien reçu votre paiement d\un montant de '. $priceFormater->format(
                $purchase->getTotalPrice() + $purchase->getShippingInfo()['price']
            ).' pour la commande n°'.$purchase->getRef().'.')
            ->html($this->twig->render('fr/email/customer/purchase_confirmation.html.twig', [
                'purchase' => $purchase,
                'isAtHomeDelivery' => $isAtHomeDelivery
            ]));

        $this->sendEmail($email);
    }
}