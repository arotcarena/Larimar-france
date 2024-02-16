<?php
namespace App\Controller\Fr\Account;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route(
        [
        '/fr/mon-compte', 
        '/fr/mon-compte/mes-informations', 
        '/fr/mon-compte/mes-adresses'
        ], 
        name: 'fr_account_index'
    )]
    public function index(): Response
    {
        return $this->render('fr/account/index.html.twig');
    }

    #[Route('/fr/mon-compte/mes-commandes', name: 'fr_account_purchase')]
    public function purchase(): Response
    {
        return $this->render('fr/account/index.html.twig');
    }

}