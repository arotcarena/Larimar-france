<?php
namespace App\Controller\Fr\Api\Account;

use Exception;
use App\Entity\Cart;
use App\Config\SiteConfig;
use App\Service\CartService;
use App\Service\StockService;
use App\Service\StripeService;
use App\Repository\CartRepository;
use App\Persister\PurchasePersister;
use App\Repository\PurchaseRepository;
use App\Service\ShippingCostCalculator;
use Doctrine\ORM\EntityManagerInterface;
use App\Convertor\Fr\CartToArrayConvertor;
use App\Convertor\PurchaseToArrayConvertor;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

#[IsGranted('ROLE_USER')]
class ApiPurchaseController extends AbstractController
{
    public function __construct(
        private CartRepository $cartRepository,
        private CartToArrayConvertor $cartConvertor,
        private ShippingCostCalculator $shippingCostCalculator,
        private PurchasePersister $purchasePersister,
        private PurchaseRepository $purchaseRepository,
        private EntityManagerInterface $em,
        private CartService $cartService,
        private StockService $stockService,
        private StripeService $stripeService,
        private PaginatorInterface $paginator,
        private PurchaseToArrayConvertor $purchaseConvertor
    )
    {

    }

    #[Route('/fr/api/purchase/findPaginatedLight', name: 'fr_api_purchase_findPaginatedLight')]
    public function findPaginatedLight(Request $request): JsonResponse
    {
        $query = $this->em->createQueryBuilder()
                            ->select('p')
                            ->from('App\Entity\Purchase', 'p')
                            ->andWhere('p.user = :user')
                            ->setParameter('user', $this->getUser())
                            ->andWhere('p.status != :status')
                            ->setParameter('status', SiteConfig::STATUS_PENDING)
                            ->orderBy('p.createdAt', 'DESC')
                            ->getQuery()
                            ;

        $currentPage = $request->query->get('page', 1);
        $perPage = $request->query->get('limit', 5);

        /** @var PaginationInterface */
        $pagination = $this->paginator->paginate(
            $query,
            $currentPage,
            $perPage
        );
        $maxPage = ceil($pagination->getTotalItemCount() / $perPage);
        return $this->json([
            'purchases' => $this->purchaseConvertor->convert(
                $pagination->getItems(), 'light'
            ),
            'count' => $pagination->getTotalItemCount(),
            'currentPage' => $currentPage,
            'maxPage' => $maxPage,
            'perPage' => $perPage
        ]);
    }

    #[Route('/fr/api/purchase/{id}/findOneFull', name: 'fr_api_purchase_findOneFull')]
    public function findOneFull(int $id): JsonResponse
    {
        $purchase = $this->purchaseRepository->find($id);
        if(!$purchase)
        {
            throw new NotFoundResourceException('Aucune purchase avec l\'id '.$id.' - ApiPurchaseController line 77');
        }
        return $this->json(
            $this->purchaseConvertor->convert($purchase, 'full')
        );
    }

    #[Route('/fr/api/purchase/lastVerificationBeforePayment', name: 'fr_api_purchase_lastVerificationBeforePayment', methods: ['POST'])]
    public function lastVerificationBeforePayment(Request $request): JsonResponse 
    {
        //on extrait les données reçues
        $data = json_decode($request->getContent());
        
        $clientSecret = $data->piSecret;
        $checkoutData = $data->checkoutData;
        if(!$clientSecret || !$checkoutData)
        {
            return $this->json([
                'errors' => ['Une erreur est survenue. Veuillez réactualiser la page']
            ], 500);
        }
        //on récupère le cart depuis la session, en updatant le stock au passage
        /** @var Cart $cart */
        [$cart, $stockStatus] = $this->cartService->getFullCart();

        // on vérifie le stock 
        if($stockStatus !== CartService::STOCK_SUFFICIENT)
        {
            if($stockStatus === CartService::CART_REMOVED)
            {
                $target = 'home';
                $message = 'Nous sommes désolé, le stock est insuffisant. La commande n\'a pas été traitée et aucun paiement n\'a été effectué.';
            }
            else
            {
                $target = 'fr_purchase_create';
                $message =  'Nous sommes désolé, le stock est insuffisant. La commande n\'a pas été traitée et aucun paiement n\'a été effectué. Votre panier a été mis à jour et si vous le souhaitez vous pouvez maintenant procéder au paiement.';
            }
            $this->addFlash('danger', $message);
            return $this->json([
                'errors' => [
                    'target' => $this->generateUrl($target)
                ]
            ], 500);
        }

        //on récupère paymentIntent et on compare prix total (s'il a changé c'est qu'un produit a changé de prix)
        $paymentIntent = $this->stripeService->retrievePaymentIntent($clientSecret);
        if($paymentIntent->amount !== ($cart->getTotalPrice() + $checkoutData->deliveryMethod->price))
        {
            $this->addFlash('danger', 'Il semble y avoir eu une modification de votre panier');
            return $this->json([
                'errors' => [
                    'target' => $this->generateUrl('purchase_create')
                ]
            ], 500);
        }

        //on récupère la purchase depuis paymentIntent
        $purchaseId = $paymentIntent->metadata->purchaseId;
        $purchase = $this->purchaseRepository->find($purchaseId);
        if(!$purchase)
        {
            return $this->json([
                'errors' => ['Erreur']
            ], 500);
        }
        //on vérifie si la purchase est déjà payée
        // ajouter éventuellement ceci pour vérifier si une purchase identique est déjà payée : $this->purchaseRepository->findDuplicateNotPendingPurchase($cart, $this->getUser())
        if($purchase->getStatus() !== SiteConfig::STATUS_PENDING)
        {
            return $this->json([
                'errors' => ['La commande a déjà été payée']
            ], 500);
        }
        
        //on update et valide la purchase avec checkoutData et cart
        $success = $this->purchasePersister->persist($purchase, $cart, $checkoutData, $this->getUser());
        if(!$success)
        {
            return $this->json([
                'errors' => ['Le formulaire comporte des erreurs']
            ], 500);
        }

        return $this->json('ok');
    }


}


