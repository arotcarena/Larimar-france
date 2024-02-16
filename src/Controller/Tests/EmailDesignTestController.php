<?php 
namespace App\Controller\Tests;

use App\Config\SiteConfig;
use App\Repository\PurchaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EmailDesignTestController extends AbstractController
{
    public function __construct(
        private PurchaseRepository $purchaseRepository
    )
    {

    }

    // PURCHASE CONFIRMATION

    #[Route('/purchaseConfirmation')]
    public function purchaseConfirmation()
    {
        $purchase = $this->purchaseRepository->findOneBy([]);
        $purchase->setShippingInfo([
            'name' => 'Mondial Relay',
            'mode' => SiteConfig::DELIVERY_MODE_RELAY,
            'relay' => [
                'name' => 'Boulangerie la mie d\'antan',
                'lineOne' => '22 cours Pasteur',
                'lineTwo' => 'Bis ter',
                'postcode' => '40500',
                'city' => 'Mont-de-Morcenx'
            ],
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('fr/email/customer/purchase_confirmation.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('fr_account_purchase')
        ]);
    }

    #[Route('/enPurchaseConfirmation')]
    public function enPurchaseConfirmation()
    {
        $purchase = $this->purchaseRepository->findOneBy([]);
        $purchase->setShippingInfo([
            'name' => 'Colissimo contre signature',
            'mode' => SiteConfig::DELIVERY_MODE_HOME,
            'relay' => null,
            'price' => 450
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('en/email/customer/purchase_confirmation.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('en_account_purchase')
        ]);
    }



    //PURCHASE STATUS

    #[Route('/purchaseStatusPaid')]
    public function purchaseStatusPaid()
    {
        $purchase = $this->purchaseRepository->findOneBy(['status' => SiteConfig::STATUS_PAID]);
        $purchase->setShippingInfo([
            'name' => 'Mondial Relay',
            'mode' => SiteConfig::DELIVERY_MODE_RELAY,
            'relay' => [
                'name' => 'Boulangerie la mie d\'antan',
                'lineOne' => '22 cours Pasteur',
                'lineTwo' => 'Bis ter',
                'postcode' => '40500',
                'city' => 'Mont-de-Morcenx'
            ],
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('fr/email/customer/purchaseStatus/paid_status.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('fr_account_purchase')
        ]);
    }
    #[Route('/enPurchaseStatusPaid')]
    public function enPurchaseStatusPaid()
    {
        $purchase = $this->purchaseRepository->findOneBy(['status' => SiteConfig::STATUS_PAID]);
        $purchase->setShippingInfo([
            'name' => 'Mondial Relay',
            'mode' => SiteConfig::DELIVERY_MODE_RELAY,
            'relay' => [
                'name' => 'Boulangerie la mie d\'antan',
                'lineOne' => '22 cours Pasteur',
                'lineTwo' => 'Bis ter',
                'postcode' => '40500',
                'city' => 'Mont-de-Morcenx'
            ],
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('en/email/customer/purchaseStatus/paid_status.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('en_account_purchase')
        ]);
    }

    #[Route('/purchaseStatusSent')]
    public function purchaseStatusSent()
    {
        $purchase = $this->purchaseRepository->findOneBy(['ref' => 'purchase_test_one_product_over_stock']);
        $purchase->setTracking('track1234')
                ->setStatus(SiteConfig::STATUS_SENT);
        $purchase->setShippingInfo([
            'name' => 'Mondial Relay',
            'mode' => SiteConfig::DELIVERY_MODE_RELAY,
            'relay' => [
                'name' => 'Boulangerie la mie d\'antan',
                'lineOne' => '22 cours Pasteur',
                'lineTwo' => 'Bis ter',
                'postcode' => '40500',
                'city' => 'Mont-de-Morcenx'
            ],
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('fr/email/customer/purchaseStatus/sent_status.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('fr_account_purchase')
        ]);
    }
    #[Route('/enPurchaseStatusSent')]
    public function enPurchaseStatusSent()
    {
        $purchase = $this->purchaseRepository->findOneBy(['ref' => 'purchase_test_one_product_over_stock']);
        $purchase->setTracking('track1234')
                ->setStatus(SiteConfig::STATUS_SENT);
        $purchase->setShippingInfo([
            'name' => 'Mondial Relay',
            'mode' => SiteConfig::DELIVERY_MODE_RELAY,
            'relay' => [
                'name' => 'Boulangerie la mie d\'antan',
                'lineOne' => '22 cours Pasteur',
                'lineTwo' => 'Bis ter',
                'postcode' => '40500',
                'city' => 'Mont-de-Morcenx'
            ],
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('en/email/customer/purchaseStatus/sent_status.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('en_account_purchase')
        ]);
    }

    #[Route('/purchaseStatusDelivered')]
    public function purchaseStatusDelivered()
    {
        $purchase = $this->purchaseRepository->findOneBy(['ref' => 'purchase_test_one_product_over_stock']);
        $purchase->setTracking('track1234')
                ->setStatus(SiteConfig::STATUS_DELIVERED);
        $purchase->setShippingInfo([
            'name' => 'Mondial Relay',
            'mode' => SiteConfig::DELIVERY_MODE_HOME,
            'relay' => null,
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('fr/email/customer/purchaseStatus/delivered_status.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('fr_account_purchase')
        ]);
    }
    #[Route('/enPurchaseStatusDelivered')]
    public function enPurchaseStatusDelivered()
    {
        $purchase = $this->purchaseRepository->findOneBy(['ref' => 'purchase_test_one_product_over_stock']);
        $purchase->setTracking('track1234')
                ->setStatus(SiteConfig::STATUS_DELIVERED);
        $purchase->setShippingInfo([
            'name' => 'Mondial Relay',
            'mode' => SiteConfig::DELIVERY_MODE_RELAY,
            'relay' => [
                'name' => 'Boulangerie la mie d\'antan',
                'lineOne' => '22 cours Pasteur',
                'lineTwo' => 'Bis ter',
                'postcode' => '40500',
                'city' => 'Mont-de-Morcenx'
            ],
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('en/email/customer/purchaseStatus/delivered_status.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('en_account_purchase')
        ]);
    }

    #[Route('/purchaseStatusCanceled')]
    public function purchaseStatusCanceled()
    {
        $purchase = $this->purchaseRepository->findOneBy(['ref' => 'purchase_test_one_product_over_stock']);
        $purchase->setTracking('track1234')
                ->setStatus(SiteConfig::STATUS_CANCELED);
        $purchase->setShippingInfo([
            'name' => 'Colissimo contre signature',
            'mode' => SiteConfig::DELIVERY_MODE_HOME,
            'relay' => null,
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('fr/email/customer/purchaseStatus/canceled_status.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('fr_account_purchase')
        ]);
    }
    #[Route('/enPurchaseStatusCanceled')]
    public function enPurchaseStatusCanceled()
    {
        $purchase = $this->purchaseRepository->findOneBy(['ref' => 'purchase_test_one_product_over_stock']);
        $purchase->setTracking('track1234')
                ->setStatus(SiteConfig::STATUS_CANCELED);
        $purchase->setShippingInfo([
            'name' => 'Mondial Relay',
            'mode' => SiteConfig::DELIVERY_MODE_RELAY,
            'relay' => [
                'name' => 'Boulangerie la mie d\'antan',
                'lineOne' => '22 cours Pasteur',
                'lineTwo' => 'Bis ter',
                'postcode' => '40500',
                'city' => 'Mont-de-Morcenx'
            ],
            'price' => 550
        ]);
        $isAtHomeDelivery = $purchase->getShippingInfo()['mode'] === SiteConfig::DELIVERY_MODE_HOME;

        return $this->render('en/email/customer/purchaseStatus/canceled_status.html.twig', [
            'purchase' => $purchase,
            'isAtHomeDelivery' => $isAtHomeDelivery,
            'link' => $this->generateUrl('en_account_purchase')
        ]);
    }
    

    //SECURITY

    #[Route('/confirmationEmail')]
    public function confirmationEmail()
    {
        return $this->render('fr/email/security/confirmation_email.html.twig', [
            'link' => 'jkdlfsf2123456fdsfjkdsqfd54646dsqfdjkdhsqkfd'
        ]);
    }

    #[Route('/enConfirmationEmail')]
    public function enConfirmationEmail()
    {
        return $this->render('en/email/security/confirmation_email.html.twig', [
            'link' => 'jkdlfsf2123456fdsfjkdsqfd54646dsqfdjkdhsqkfd'
        ]);
    }

    #[Route('/changeEmail')]
    public function changeEmail()
    {
        return $this->render('fr/email/security/email_change_email.html.twig', [
            'code' => '123546'
        ]);
    }

    #[Route('/enChangeEmail')]
    public function enChangeEmail()
    {
        return $this->render('en/email/security/email_change_email.html.twig', [
            'code' => '123546'
        ]);
    }

    #[Route('/resetPasswordEmail')]
    public function resetPasswordEmail()
    {
        return $this->render('fr/email/security/reset_password_email.html.twig', [
            'link' => 'jkdlfsf2123456fdsfjkdsqfd54646dsqfdjkdhsqkfd'
        ]);
    }

    #[Route('/enResetPasswordEmail')]
    public function enResetPasswordEmail()
    {
        return $this->render('en/email/security/reset_password_email.html.twig', [
            'link' => 'jkdlfsf2123456fdsfjkdsqfd54646dsqfdjkdhsqkfd'
        ]);
    }
}