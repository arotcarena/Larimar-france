<?php
namespace App\Controller\En\Shop;

use App\Entity\Product;
use App\Service\ProductCountService;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository,
        private ProductCountService $productCountService
    )
    {

    }


    #[Route('/en/{slug}_{publicRef}.html', name: 'en_product_show', requirements: ['slug' => '[a-z0-9-]+'])]
    #[Route('/en/{categorySlug}/{slug}_{publicRef}.html', name: 'en_product_show_withCategory', requirements: [
            'slug' => '[a-z0-9-]+',
            'categorySlug' => '[a-z0-9-]+'
        ])]
    #[Route('/en/{categorySlug}/{subCategorySlug}/{slug}_{publicRef}.html', name: 'en_product_show_withCategoryAndSubCategory', requirements: [
            'slug' => '[a-z0-9-]+',
            'subCategorySlug' => '[a-z0-9-]+',
            'categorySlug' => '[a-z0-9-]+'
        ]
    )]
    public function show(string $slug, string $publicRef, ?string $categorySlug = null, ?string $subCategorySlug = null): Response
    {
        /** @var Product */
        $product = $this->productRepository->findOneByPublicRef($publicRef);
        //on vérifie que le product existe et que les params dans l'url sont bons
        if(
            $product === null 
            || 
            $product->getEnSlug() !== $slug 
            ||
            ($product->getCategory() && $categorySlug !== $product->getCategory()->getEnSlug()) 
            ||
            ($product->getSubCategory() && $subCategorySlug !== $product->getSubCategory()->getEnSlug())
        )
        {
            throw new NotFoundResourceException('La page que vous recherchez n\'existe pas');
        }
        //on ajoute une vue au product si nécessaire
        $this->productCountService->countView($product);
        //on render le product
        return $this->render('en/shop/product/show.html.twig', [
            'product' => $product
        ]);
    }

    #[Route('/en/search', name: 'en_product_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('en/shop/product/index.html.twig');
    }

    

    
}