<?php
namespace App\Email\En\Customer;

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
            ->subject('Order confirmation')
            ->text('Thank you for your order ! We have received your payment of '. $priceFormater->format(
                $purchase->getTotalPrice() + $purchase->getShippingInfo()['price']
            ).' for the order '.$purchase->getRef().'.')
            ->html($this->twig->render('en/email/customer/purchase_confirmation.html.twig', [
                'purchase' => $purchase,
                'isAtHomeDelivery' => $isAtHomeDelivery
            ]));

        $this->sendEmail($email);
    }
}