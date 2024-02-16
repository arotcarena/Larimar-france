<?php
namespace App\Controller\Admin\Api;

use App\Repository\ProductRepository;
use App\Convertor\Fr\ProductToArrayConvertor;
use App\Repository\PictureRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



#[IsGranted('ROLE_ADMIN')]
class ApiAdminProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductToArrayConvertor $productToArrayConvertor,
        private PictureRepository $pictureRepository
    )
    {

    }

    #[Route('/admin/api/product/{id}/suggestedProducts', name: 'admin_api_product_suggestedProducts')]
    public function suggestedProducts(int $id): JsonResponse
    {
        $product = $this->productRepository->find($id);
        if(!$product)
        {
            return new JsonResponse([
                'errors' => ['Le product avec l\'id '.$id.'n\'existe pas']
            ], 500);
        }
        $suggestedProducts = $product->getSuggestedProducts();
        $this->pictureRepository->hydrateProductsWithFirstPicture($suggestedProducts);
        return new JsonResponse(
            $this->productToArrayConvertor->convert($suggestedProducts->toArray())
        );
    }
}