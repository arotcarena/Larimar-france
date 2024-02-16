<?php
namespace App\Controller\Fr\Api\Contact;

use App\Repository\ProductRepository;
use App\Email\Admin\AdminNotificationEmail;
use App\Form\DataModel\FingerSizeContact;
use Exception;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiContactController extends AbstractController
{
    public function __construct(
        private AdminNotificationEmail $adminNotificationEmail,
        private ProductRepository $productRepository,
        private ValidatorInterface $validator
    )
    {

    }

    #[Route('/fr/api/contact/fingerSize', name: 'fr_api_contact_fingerSize')]
    public function fingerSize(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());
        try 
        {
            $product = $this->productRepository->findOneBy(['publicRef' => $data->publicRef]);

            $fingerSizeContact = new FingerSizeContact;
            $fingerSizeContact->setEmail($data->email)
                                ->setFingerSize($data->fingerSize)
                                ;
            $errors = $this->validator->validate($fingerSizeContact);
            if(count($errors) === 0)
            {
                $this->adminNotificationEmail->send(
                    'Demande de taille "'.$fingerSizeContact->getFingerSize().'" pour le produit "'.$product->getDesignation().' (Réf. publique: '.$product->getPublicRef().')". Répondre à '.$fingerSizeContact->getEmail()
                );
                return new JsonResponse('ok');
            }
        }
        catch(Exception $e)
        {
            return new JsonResponse('', 500);
        }
    }
}