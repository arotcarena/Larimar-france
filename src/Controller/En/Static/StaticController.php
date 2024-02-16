<?php
namespace App\Controller\En\Static;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StaticController extends AbstractController
{
    #[Route('/en/who-am-i', name: 'en_static_aPropos')]
    public function aPropos()
    {
        return $this->render('en/static/a_propos.html.twig');
    }
}