<?php
namespace App\Email\En\Customer;

use App\Config\SiteConfig;
use App\Email\EmailFactory;
use App\Entity\Purchase;
use Symfony\Component\Mime\Email;

class PurchaseStatusEmail extends EmailFactory
{
    public function send(Purchase $purchase, string $newStatus): void
    {
        switch($newStatus) 
        {
            case SiteConfig::STATUS_PAID:
                $this->sendEmail($this->createPaidStatusEmail($purchase));
                break;
            case SiteConfig::STATUS_SENT:
                $this->sendEmail($this->createSentStatusEmail($purchase));
                break;
            case SiteConfig::STATUS_DELIVERED:
                $this->sendEmail($this->createDeliveredStatusEmail($purchase));
                break;
            case SiteConfig::STATUS_CANCELED:
                $this->sendEmail($this->createCanceledStatusEmail($purchase));
                break;
            default:
                return;
            
        }
    }

    public function createPaidStatusEmail(Purchase $purchase): Email 
    {
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;
        
        return (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($purchase->getUser()->getEmail())
            ->subject('Your order is validated !')
            ->text('We have received your payment for the order '.$purchase->getRef())
            ->html($this->twig->render('en/email/customer/purchaseStatus/paid_status.html.twig', [
                'purchase' => $purchase,
                'isAtHomeDelivery' => $isAtHomeDelivery,
            ]));
    }

    public function createSentStatusEmail(Purchase $purchase): Email 
    {
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($purchase->getUser()->getEmail())
            ->subject('Your order has been sent !')
            ->text('Your order '.$purchase->getRef().' has been sent')
            ->html($this->twig->render('en/email/customer/purchaseStatus/sent_status.html.twig', [
                'purchase' => $purchase,
                'isAtHomeDelivery' => $isAtHomeDelivery,
            ]));
    }

    public function createDeliveredStatusEmail(Purchase $purchase): Email
    {
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($purchase->getUser()->getEmail())
            ->subject('Your order has been delivered !')
            ->text('Your order '.$purchase->getRef().' has been delivered')
            ->html($this->twig->render('en/email/customer/purchaseStatus/delivered_status.html.twig', [
                'purchase' => $purchase,
                'isAtHomeDelivery' => $isAtHomeDelivery,
            ]));
    }

    public function createCanceledStatusEmail(Purchase $purchase): Email
    {
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return (new Email())
            ->from(SiteConfig::EMAIL_NOREPLY)
            ->to($purchase->getUser()->getEmail())
            ->subject('Your order has been canceled !')
            ->text('Your order'.$purchase->getRef().' has been canceled')
            ->html($this->twig->render('en/email/customer/purchaseStatus/canceled_status.html.twig', [
                'purchase' => $purchase,
                'isAtHomeDelivery' => $isAtHomeDelivery,
            ]));

    }
}