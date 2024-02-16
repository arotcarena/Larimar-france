<?php
namespace App\Controller\Fr\Shop\Category;

use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class CategoryController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository
    )
    {

    }

    #[Route('/fr/{slug}.html', name: 'fr_category_show', requirements: ['slug' => '[a-z0-9-]+'])]
    public function index(string $slug): Response
    {
        $category = $this->categoryRepository->findOneBySlug($slug, 'fr');
        if($category === null)
        {
            throw new NotFoundResourceException('La page que vous recherchez n\'existe pas');
        }

        return $this->render('fr/shop/category/show.html.twig', [
            'category' => $category
        ]);
    }
}