<?php
namespace App\Controller\En\Account;

use Exception;
use App\Config\SiteConfig;
use App\Service\CartService;
use App\Service\StockService;
use App\Service\StripeService;
use App\Helper\FrDateTimeGenerator;
use App\Service\ProductCountService;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Email\Admin\AdminNotificationEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Email\Admin\AdminPurchaseConfirmationEmail;
use App\Twig\Runtime\PriceFormaterExtensionRuntime;
use App\Email\En\Customer\PurchaseConfirmationEmail;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

#[IsGranted('ROLE_USER')]
class PurchaseController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private PurchaseRepository $purchaseRepository,
        private FrDateTimeGenerator $frDateTimeGenerator,
        private EntityManagerInterface $em,
        private PurchaseConfirmationEmail $purchaseConfirmationEmail,
        private AdminNotificationEmail $adminNotificationEmail,
        private AdminPurchaseConfirmationEmail $adminPurchaseConfirmationEmail,
        private PriceFormaterExtensionRuntime $priceFormater,
        private StockService $stockService,
        private StripeService $stripeService,
        private ProductCountService $productCountService
    )
    {

    }

    #[Route('/en/checkout', name: 'en_purchase_create')]
    public function create(): Response
    {
        if($this->cartService->count() === 0)
        {
            throw new NotFoundResourceException('Your cart is empty, you cannot access checkout page.');
        }
        return $this->render('en/customer/purchase/create.html.twig');
    }

    #[Route('/en/order-successfull', name: 'en_purchase_paymentSuccess')]
    public function paymentSuccess(Request $request): Response 
    {
        //on récupére le paymentIntent à partir des infos dans l'url
        $paymentIntent = $this->stripeService->retrievePaymentIntent(
            $request->query->get('payment_intent_client_secret', ''), 
            $request->query->get('payment_intent', '')
        );

        //on récupère la purchase
        $purchase = $this->purchaseRepository->find($paymentIntent->metadata->purchaseId);
        if(!$purchase)
        {
            throw new NotFoundResourceException('An unexpected problem has occured. Please contact customer service.');
        }

        //on vérifie que la commande n'est pas déjà payée
        if($purchase->getStatus() !== SiteConfig::STATUS_PENDING)
        {
            $this->adminNotificationEmail->send('Une commande a probablement été payée 2 fois. Réf: '.$purchase->getRef().', email: '.$purchase->getUser()->getEmail());
            throw new Exception('An unexpected problem has occured. Please contact customer service.');
        }

        //on vérifie que le montant payé est bien le prix total de la purchase
        if($paymentIntent->amount_received !== ($purchase->getTotalPrice() + $purchase->getShippingInfo()['price']))
        {
            $this->adminNotificationEmail->send(
                'Un problème a eu lieu sur une commande. Le montant réglé est différent du montant total de la commande. Réf. '.$purchase->getRef().'. Adresse email : '.$purchase->getUser()->getEmail()
            );
            throw new Exception('An unexpected problem has occured. Please contact customer service.');
        }

        //vérifie les stocks
        //vérifie que les prix des produits sont toujours identiques aux prix dans la purchase
        //si tout est bon update les stocks du shop
        if(!$this->stockService->verifyPurchaseStocksAndPriceAndUpdateStocksIfOk($purchase))
        {
            $this->adminNotificationEmail->send(
                'Un problème a eu lieu sur une commande. Le stock a du être modifié juste au moment du paiement, le client a payé une commande mais les stocks sont insuffisant. Réf. '.$purchase->getRef().'. Adresse email : '.$purchase->getUser()->getEmail()
            );
            throw new Exception('An unexpected problem has occured. Please contact customer service.');
        }

        //on marque la commande comme payée
        $purchase->setStatus(SiteConfig::STATUS_PAID);
        $purchase->setPaidAt($this->frDateTimeGenerator->generateImmutable());
        $purchase->setLang('en'); // pour que quand l'admin modifiera le statut le mail soit envoyé dans la bonne langue
        $this->em->flush();

        //on ajoute les sales aux products concernés
        $this->productCountService->countSales($purchase);

        //on vide le panier
        $this->cartService->empty();

        //envoi du mail de confirmation de commande
        $this->purchaseConfirmationEmail->send($purchase);
        $this->adminPurchaseConfirmationEmail->send($purchase);


        $this->addFlash('success', 'Thank you for your order. We have received your payment of '.$this->priceFormater->format($paymentIntent->amount_received).'. A summary email has been sent to you. Find all your orders in "My account" section');
        return $this->redirectToRoute('en_home');
    }
}