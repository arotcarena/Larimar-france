<?php
namespace App\Controller\Fr\Shop\Category;

use App\Repository\SubCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class SubCategoryController extends AbstractController
{
    public function __construct(
        private SubCategoryRepository $subCategoryRepository
    )
    {

    }

    #[Route(
        '/fr/{categorySlug}/{subCategorySlug}.html', 
        name: 'fr_subCategory_show', 
        requirements: [
            'categorySlug' => '[a-z0-9-]+', 
            'subCategorySlug' => '[a-z0-9-]+'
            ]
    )]
    public function index(string $categorySlug, string $subCategorySlug): Response
    {
        $subCategory = $this->subCategoryRepository->findOneByBothSlugs($categorySlug, $subCategorySlug);
        if($subCategory === null)
        {
            throw new NotFoundResourceException('La page que vous recherchez n\'existe pas');
        }

        return $this->render('fr/shop/category/subCategory/show.html.twig', [
            'subCategory' => $subCategory,
            'category' => $subCategory->getParentCategory()
        ]);
    }
}