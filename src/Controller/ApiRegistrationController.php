<?php
namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ApiRegistrationController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository
    )
    {

    }

    #[Route('/api/security/registration/uniqueEmailValidation', name: 'api_security_registration_uniqueEmailValidation', methods: ['POST'])]
    public function uniqueEmailValidation(Request $request): JsonResponse
    {
        $email = json_decode($request->getContent());

        $existingUser = $this->userRepository->findOneByEmail($email);

        if($existingUser === null)
        {
            return $this->json('ok');
        }
        return $this->json('', 500);
    }
}