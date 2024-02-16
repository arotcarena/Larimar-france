<?php
namespace App\Controller\Fr\Api\Shop;

use Exception;
use App\Service\CartService;
use App\Convertor\Fr\CartToArrayConvertor;
use App\CustomException\NotEnoughException;
use App\CustomException\OverStockException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Twig\Runtime\PriceFormaterExtensionRuntime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiCartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
        private CartToArrayConvertor $cartConvertor,
        private PriceFormaterExtensionRuntime $priceFormater
    )
    {

    }

    #[Route(
        '/fr/api/cart/add/id-{productId}_quantity-{quantity}', 
        name: 'fr_api_cart_add', 
        methods: ['GET'], 
        requirements: [
            'productId' => '\d+',
            'quantity' => '\d+'
        ]
    )]
    public function add(int $productId, int $quantity): JsonResponse
    {
        try {
            $this->cartService->add($productId, $quantity);
        } catch(Exception $e) {
            return new JsonResponse([
                'errors' => $e->getMessage()
            ], 500);
        }
        return new JsonResponse('ok');
    }

    #[Route(
        '/fr/api/cart/less/id-{productId}_quantity-{quantity}', 
        name: 'fr_api_cart_less',
        methods: ['GET'],
        requirements: [
            'productId' => '\d+',
            'quantity' => '\d+'
        ]
    )]
    public function less(int $productId, int $quantity): JsonResponse 
    {
        try{
            $this->cartService->less($productId, $quantity);
        } catch(NotEnoughException $e) {
            return new JsonResponse([
                'errors' => $e->getMessage()
            ], 500);
        } 
        return new JsonResponse('ok');
    }

    #[Route(
        '/fr/api/cart/remove/id-{productId}', 
        name: 'fr_api_cart_remove',
        methods: ['GET'],
        requirements: [
            'productId' => '\d+'
        ]
    )]
    public function remove(int $productId): JsonResponse 
    {
        $this->cartService->remove($productId);
        return new JsonResponse('ok');
    }

    #[Route('/fr/api/cart/getFullCart', name: 'fr_api_cart_getFullCart', methods: ['GET'])]
    public function getFullCart(): JsonResponse 
    {
        [$fullCart, $stockStatus] = $this->cartService->getFullCart();
        return new JsonResponse(
            $this->cartConvertor->convert($fullCart)
        );
    }

    #[Route('/fr/api/cart/getLightCart', name: 'fr_api_cart_getLightCart', methods: ['GET'])]
    public function getLightCart(): JsonResponse 
    {
        $lightCart = $this->cartService->getLightCart();
        return new JsonResponse($lightCart);
    }

    #[Route('/fr/api/cart/count', name: 'fr_api_cart_count', methods: ['GET'])]
    public function count(): JsonResponse 
    {
        $count = $this->cartService->count();
        return new JsonResponse($count);
    }
}