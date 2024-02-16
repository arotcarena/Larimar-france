<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
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

    #[Route(path: '/', name: 'home')]
    public function index(Request $request): Response
    {
        if(!$this->isGranted('ROLE_USER'))
        {
            return $this->render('construction/index.html.twig');
        }

        $lang = substr($request->headers->get('accept-language'), 0, 2);
        if($lang !== 'fr')
        {
            return $this->redirectToRoute('en_home');
        }

        return $this->render('fr/home/index.html.twig');
    }
}