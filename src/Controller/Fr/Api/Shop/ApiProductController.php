<?php
namespace App\Controller\Fr\Api\Shop;

use App\Form\DataModel\SearchParams;
use App\Repository\ProductRepository;
use App\Convertor\Fr\ProductToArrayConvertor;
use App\Repository\PictureRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductToArrayConvertor $productToArrayConvertor,
        private PictureRepository $pictureRepository
    )
    {

    }

    #[Route('/fr/api/product/search', name: 'fr_api_product_search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        $q = $request->query->get('q');
        if($q === '')
        {
            return new JsonResponse(['products' => [], 'count' => 0]);
        }
        $limit = $request->query->get('limit', 4);
        $products = $this->productRepository->qSearch($q, $limit);
        $count = $this->productRepository->countQSearch($q);
        return new JsonResponse([
            'products' => $this->productToArrayConvertor->convert($products),
            'count' => $count
        ]);
    }

    #[Route('/fr/api/product/index', name: 'fr_api_product_index', methods: ['GET'])]
    public function index(Request $request): JsonResponse 
    {
        $currentPage = $request->query->get('page', 1);
        $perPage = $request->query->get('limit', 20);

        $searchParams = (new SearchParams)
                        ->setQ($request->query->get('q'))
                        ->setMaterial(explode('__', $request->query->get('material')))
                        ->setPage($currentPage)
                        ->setLimit($perPage)
                        ;

        $categoryId = $request->query->get('categoryId');
        if($categoryId !== 'null')
        {
            $searchParams->setCategoryId($categoryId);
        }
        $subCategoryId = $request->query->get('subCategoryId');
        if($subCategoryId !== 'null')
        {
            $searchParams->setSubCategoryId($subCategoryId);
        }

        /** @var PaginationInterface */
        $pagination = $this->productRepository->filter($searchParams);

        $count = $pagination->getTotalItemCount();
        $maxPage = ceil($count / $perPage);

        return new JsonResponse([
            'products' => $this->productToArrayConvertor->convert($pagination->getItems()),
            'count' => $count,
            'maxPage' => $maxPage,
            'perPage' => $perPage,
            'currentPage' => $currentPage
        ]);
    }

    #[Route('/fr/api/product/{id}/getSuggestedProducts', name: 'fr_api_product_getSuggestedProducts', methods: ['GET'])]
    public function getSuggestedProducts(int $id): JsonResponse
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