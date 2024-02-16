<?php
namespace App\Controller\En\Api\Account;

use App\Config\EnTextConfig;
use App\Config\TextConfig;
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
    #[Route('/en/api/user/getCivilState', name: 'en_api_user_getCivilState')]
    public function getCivilState(): JsonResponse
    {
        /** @var User */
        $user = $this->getUser();

        //on traduis la civilité en anglais (car c'est géré complètement en anglais dans le checkout form)
        if($user->getCivility() === TextConfig::CIVILITY_F)
        {
            $user->setCivility(EnTextConfig::CIVILITY_F);
        }
        elseif($user->getCivility() === TextConfig::CIVILITY_M)
        {
            $user->setCivility(EnTextConfig::CIVILITY_M);
        }

        return $this->json([
            'email' => $user->getEmail(),
            'civility' => $user->getCivility(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName()
        ]);
    }

    
    #[IsGranted('ROLE_USER')]
    #[Route('/en/api/user/setCivilState', name: 'en_api_user_setCivilState', methods: ['POST'])]
    public function setCivilState(Request $request): JsonResponse 
    {
        /** @var User */
        $user = $this->getUser();
        
        try 
        {
            $data = json_decode($request->getContent());

            //on traduis la civilité en français (dans le checkout form elle est gérée totalement en anglais)
            if($data->civility === EnTextConfig::CIVILITY_F)
            {
                $data->civility = TextConfig::CIVILITY_F;
            }
            elseif($data->civility === EnTextConfig::CIVILITY_M)
            {
                $data->civility = TextConfig::CIVILITY_M;
            }

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