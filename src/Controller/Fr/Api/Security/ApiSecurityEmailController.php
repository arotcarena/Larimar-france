<?php
namespace App\Controller\Fr\Api\Security;

use App\Email\Fr\Security\EmailChangeEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiSecurityEmailController extends AbstractController
{
    public function __construct(
        private EmailChangeEmail $emailChangeEmail
    )
    {

    }

    #[Route('/fr/api/security/changeEmailConfirmationEmail', name: 'fr_api_security_changeEmailConfirmationEmail', methods: ['POST'])]
    public function changeEmailConfirmationEmail(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        if(!isset($data->email) || !isset($data->token))
        {
            return $this->json('', 500);
        }
        $this->emailChangeEmail->send($data->email, $data->token);
        return $this->json('ok');
    }
}