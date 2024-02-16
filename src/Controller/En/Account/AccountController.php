<?php
namespace App\Controller\En\Account;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class AccountController extends AbstractController
{
    #[Route(
        [
        '/en/my-account', 
        '/en/my-account/details', 
        '/en/my-account/addresses'
        ], 
        name: 'en_account_index'
    )]
    public function index(): Response
    {
        return $this->render('en/account/index.html.twig');
    }

    #[Route('/en/my-account/orders', name: 'en_account_purchase')]
    public function purchase(): Response
    {
        return $this->render('en/account/index.html.twig');
    }
}