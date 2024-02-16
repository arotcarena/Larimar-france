<?php
namespace App\Controller\Fr\Api\Account;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiUserController extends AbstractController
{
    public function __construct(
        private ValidatorInterface $validator,
        private EntityManagerInterface $em
    )
    {

    }

    
    /**
     * Ne pas mettre de restriction auth car sinon cette route est retenue comme target pour le futur login
     *
     * @return JsonResponse
     */
    #[Route('/fr/api/user/getCivilState', name: 'fr_api_user_getCivilState')]
    public function getCivilState(): JsonResponse
    {
        /** @var User */
        $user = $this->getUser();

        return $this->json([
            'email' => $user->getEmail(),
            'civility' => $user->getCivility(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
        ]);
    }

    #[IsGranted('ROLE_USER')]
    #[Route('/fr/api/user/setCivilState', name: 'fr_api_user_setCivilState', methods: ['POST'])]
    public function setCivilState(Request $request): JsonResponse 
    {
        /** @var User */
        $user = $this->getUser();
        
        try 
        {
            $data = json_decode($request->getContent());
            if(!is_string($data->civility) || !is_string($data->firstName) || !is_string($data->lastName))
            {
                throw new Exception('Le formulaire est invalide');
            }

            $user->setCivility($data->civility)
                ->setFirstName($data->firstName)
                ->setLastName($data->lastName)
                ;
            if(isset($data->email))
            {
                $user->setEmail($data->email);
            }
        
            $errors = $this->validator->validate($user);
            if(count($errors) !== 0)
            {
                throw new Exception($errors[0]->getMessage());
            }

            $this->em->flush($user);
            return $this->json('ok');
        }
        catch(Exception $e)
        {
            return $this->json([
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }
}