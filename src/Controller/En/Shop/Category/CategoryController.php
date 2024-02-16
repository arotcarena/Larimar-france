<?php
namespace App\Controller\En\Shop\Category;

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

    #[Route('/en/{slug}.html', name: 'en_category_show', requirements: ['slug' => '[a-z0-9-]+'])]
    public function index(string $slug): Response
    {
        $category = $this->categoryRepository->findOneByEnSlug($slug);
        if($category === null)
        {
            throw new NotFoundResourceException('La page que vous recherchez n\'existe pas');
        }

        return $this->render('en/shop/category/show.html.twig', [
            'category' => $category
        ]);
    }
}