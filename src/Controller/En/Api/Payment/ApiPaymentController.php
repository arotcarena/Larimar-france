<?php
namespace App\Controller\En\Api\Payment;

use App\Entity\Purchase;
use App\Helper\FrDateTimeGenerator;
use App\Repository\PurchaseRepository;
use App\Service\CartService;
use App\Service\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class ApiPaymentController extends AbstractController
{
    public function __construct(
        private PurchaseRepository $purchaseRepository,
        private StripeService $stripeService,
        private CartService $cartService,
        private EntityManagerInterface $em,
        private FrDateTimeGenerator $frDateTimeGenerator
    )
    {
        
    }

    #[Route('/en/api/payment/createPaymentIntent', name: 'en_api_payment_createPaymentIntent')]
    public function createPaymentIntent(Request $request): JsonResponse 
    {
        //le totalPrice est envoyé depuis le javascript mais seulement si le cart avait eu le temps de charger.
        //sinon il faut le récupérer avec cartService
        $data = json_decode($request->getContent());

        if($data)
        {
            $totalPrice = $data->totalPrice;
            $shippingCost = $data->shippingCost;
        }
        else
        {
            $shippingCost = 0;
            $totalPrice = null;
        }
        
        if(!$totalPrice)
        {
            $lightCart = $this->cartService->getLightCart();
            $totalPrice = $lightCart['totalPrice'];
        }
        
        //on vérifie si égal à 0
        if($totalPrice === 0)
        {
            return $this->json([
                'errors' => ['Votre panier est vide']
            ], 500);
        }

        //on crée une purchase vide pour pouvoir passer l'id au paymentIntent
        $purchase = new Purchase;
        $this->em->persist($purchase);
        $this->em->flush();

        //on crée le paymentIntent et on renvoie le clientSecret
        $paymentIntent = $this->stripeService->createPaymentIntent(
            ($totalPrice + $shippingCost), 
            ['purchaseId' => $purchase->getId()]     // ce purchaseId sera ensuite passé par stripe à mon webhook qui écoute l'event payment_intent_succeeded
        );
        if(!$paymentIntent)
        {
            return $this->json([
                'errors' => ['Un problème est survenu. Veuillez réactualiser la page']
            ], 500);
        }
        return $this->json([
            'clientSecret' => $paymentIntent->client_secret,
        ]);
    }

}