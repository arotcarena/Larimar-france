<?php
namespace App\Controller\Fr\Static;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StaticController extends AbstractController
{
    #[Route('/fr/qui-suis-je', name: 'fr_static_aPropos')]
    public function aPropos()
    {
        return $this->render('fr/static/a_propos.html.twig');
    }
}