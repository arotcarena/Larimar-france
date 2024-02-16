<?php

namespace App\Controller\En;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function __construct(
        private CategoryRepository $categoryRepository
    )
    {

    }

    #[Route(path: '/en/', name: 'en_home')]
    public function index(): Response
    {
        return $this->render('en/home/index.html.twig');
    }
}