<?php
namespace App\Controller\Fr\Api\Account;

use App\Config\SiteConfig;
use App\Convertor\AddressToArrayConvertor;
use App\Entity\Address;
use App\Helper\FrDateTimeGenerator;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class ApiAddressController extends AbstractController
{
    public function __construct(
        private AddressRepository $addressRepository,
        private FrDateTimeGenerator $frDateTimeGenerator,
        private EntityManagerInterface $em,
        private AddressToArrayConvertor $addressConvertor
    )
    {

    }

    #[Route('/fr/api/address/index', name: 'fr_api_address_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $addresses = $this->addressRepository->findBy(['user' => $this->getUser()]);

        return $this->json(
            $this->addressConvertor->convert($addresses)
        );
    }

    #[Route('/fr/api/address/create', name: 'fr_api_address_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        if($this->addressRepository->countByUser($this->getUser()) >= SiteConfig::MAX_REGISTERED_ADDRESSES_PER_USER)
        {
            return $this->json([
                'errors' => ['Impossible d\'ajouter plus de '. SiteConfig::MAX_REGISTERED_ADDRESSES_PER_USER .' adresses']
            ], 500);
        }
        try 
        {
            $data = json_decode($request->getContent());
            $address = (new Address)
                        ->setUser($this->getUser())
                        ->setCivility($data->civility)
                        ->setFirstName($data->firstName)
                        ->setLastName($data->lastName)
                        ->setLineOne($data->lineOne)
                        ->setLineTwo($data->lineTwo)
                        ->setPostcode($data->postcode)
                        ->setCity($data->city)
                        ->setCountry($data->country)
                        ->setEnCountry($data->enCountry)
                        ->setIso($data->iso)
                        ->setContinents($data->continents)
                        ->setCreatedAt($this->frDateTimeGenerator->generateImmutable())
                    ;
            $this->em->persist($address);
            $this->em->flush();
            return $this->json($address->getId());
        }
        catch(Exception $e)
        {
            return $this->json([
                'errors' => ['L\'adresse n\'a pas pu être ajoutée, les données soumises sont invalides']
            ], 500);
        }
    }

    #[Route('/fr/api/address/{id}/update', name: 'fr_api_address_update', methods: ['POST'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $address = $this->addressRepository->find($id);
        if(!$address)
        {
            return $this->json([
                'errors' => ['Aucune Adresse ne possède l\'id "'.$id.'"']
            ], 500);
        }

        try 
        {
            $data = json_decode($request->getContent());
            $address 
                    ->setUser($this->getUser())
                    ->setCivility($data->civility)
                    ->setFirstName($data->firstName)
                    ->setLastName($data->lastName)
                    ->setLineOne($data->lineOne)
                    ->setLineTwo($data->lineTwo)
                    ->setPostcode($data->postcode)
                    ->setCity($data->city)
                    ->setCountry($data->country)
                    ->setEnCountry($data->enCountry)
                    ->setIso($data->iso)
                    ->setContinents($data->continents)
                    ;
            $this->em->flush();
            return $this->json($address->getId());
        }
        catch(Exception $e)
        {
            return $this->json([
                'errors' => ['L\'adresse n\'a pas pu être modifiée, les données soumises sont invalides']
            ], 500);
        }
    }

    #[Route('/fr/api/address/delete', name: 'fr_api_address_delete', methods: ['POST'])]
    public function delete(Request $request): JsonResponse
    {
        $id = json_decode($request->getContent());
        $address = $this->addressRepository->find($id);
        if(!$address)
        {
            return $this->json([
                'errors' => ['Aucune Adresse ne possède l\'id "'.$id.'"']
            ], 500);
        }

        $this->em->remove($address);
        $this->em->flush();

        return $this->json('ok');
    }
}