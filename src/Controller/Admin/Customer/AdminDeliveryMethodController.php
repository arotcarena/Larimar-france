<?php
namespace App\Controller\Admin\Customer;

use App\Config\SiteConfig;
use App\Form\DeliveryMethodType;
use App\Helper\FrDateTimeGenerator;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\DeliveryMethodRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class AdminDeliveryMethodController extends AbstractController
{
    public function __construct(
        private DeliveryMethodRepository $deliveryMethodRepository,
        private EntityManagerInterface $em,
        private FrDateTimeGenerator $frDateTimeGenerator
    )
    {

    }

    #[Route('/admin/deliveryMethod/index', name: 'admin_deliveryMethod_index')]
    public function index(): Response
    {
        $trackedLetter = [
            'France' => $this->deliveryMethodRepository->findBy(['name' => 'Lettre suivie', 'destinationArea' => SiteConfig::AREA_FRANCE]),
            'Outre-mer 1 (97...)' => $this->deliveryMethodRepository->findBy(['name' => 'Lettre suivie', 'destinationArea' => SiteConfig::AREA_FRANCE_OM_1]), 
            'Outre-mer 2 (98...)' => $this->deliveryMethodRepository->findBy(['name' => 'Lettre suivie', 'destinationArea' => SiteConfig::AREA_FRANCE_OM_2]), 
            'Europe (zone A)' => $this->deliveryMethodRepository->findBy(['name' => 'Lettre suivie', 'destinationArea' => SiteConfig::AREA_EUROPE]), 
            'Monde (zone B)' => $this->deliveryMethodRepository->findBy(['name' => 'Lettre suivie', 'destinationArea' => SiteConfig::AREA_WORLD_B]), 
            'Monde (zone C)' => $this->deliveryMethodRepository->findBy(['name' => 'Lettre suivie', 'destinationArea' => SiteConfig::AREA_WORLD_C]), 
        ];

        $colissimo = [
            'France' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo', 'destinationArea' => SiteConfig::AREA_FRANCE]),
            'Outre-mer 1 (97...)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo', 'destinationArea' => SiteConfig::AREA_FRANCE_OM_1]), 
            'Outre-mer 2 (98...)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo', 'destinationArea' => SiteConfig::AREA_FRANCE_OM_2]), 
            'Europe (zone A)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo', 'destinationArea' => SiteConfig::AREA_EUROPE]), 
            'Monde (zone B)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo', 'destinationArea' => SiteConfig::AREA_WORLD_B]), 
            'Monde (zone C)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo', 'destinationArea' => SiteConfig::AREA_WORLD_C]), 
        ];

        $colissimoWithSignature = [
            'FRANCE' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo contre signature', 'destinationArea' => SiteConfig::AREA_FRANCE]),
            'Outre-mer 1 (97...)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo contre signature', 'destinationArea' => SiteConfig::AREA_FRANCE_OM_1]), 
            'Outre-mer 2 (98...)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo contre signature', 'destinationArea' => SiteConfig::AREA_FRANCE_OM_2]), 
            'Europe (zone A)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo contre signature', 'destinationArea' => SiteConfig::AREA_EUROPE]), 
            'Monde (zone B)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo contre signature', 'destinationArea' => SiteConfig::AREA_WORLD_B]), 
            'Monde (zone C)' => $this->deliveryMethodRepository->findBy(['name' => 'Colissimo contre signature', 'destinationArea' => SiteConfig::AREA_WORLD_C]), 
        ];

        $mondialRelay = [
            'France' => $this->deliveryMethodRepository->findBy(['name' => 'Mondial Relay', 'destinationArea' => SiteConfig::AREA_FRANCE]),
            'Belgique & Luxembourg' => $this->deliveryMethodRepository->findBy(['name' => 'Mondial Relay', 'destinationArea' => SiteConfig::AREA_BE_LU]),
            'Pays-Bas' => $this->deliveryMethodRepository->findBy(['name' => 'Mondial Relay', 'destinationArea' => SiteConfig::AREA_NL]),
            'Espagne & Portugal' => $this->deliveryMethodRepository->findBy(['name' => 'Mondial Relay', 'destinationArea' => SiteConfig::AREA_ES_PT]),
        ];


        return $this->render('admin/customer/deliveryMethod/index.html.twig', [
            'trackedLetter' => $trackedLetter,
            'colissimo' => $colissimo,
            'colissimoWithSignature' => $colissimoWithSignature,
            'mondialRelay' => $mondialRelay
        ]);
    }

    #[Route('/admin/deliveryMethod/{id}/update', name: 'admin_deliveryMethod_update')]
    public function update(int $id, Request $request): Response
    {
        $deliveryMethod = $this->deliveryMethodRepository->find($id);
        if(!$deliveryMethod) 
        {
            throw new NotFoundResourceException('Aucun objet DeliveryMethod avec l\'id' .$id);
        }

        $form = $this->createForm(DeliveryMethodType::class, $deliveryMethod);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $deliveryMethod->setUpdatedAt(
                $this->frDateTimeGenerator->generate()
            );
            $this->em->flush();
            $this->addFlash('success', 'La méthode de livraison a bien été mise à jour');
            return $this->redirectToRoute('admin_deliveryMethod_index');
        }

        return $this->render('admin/customer/deliveryMethod/update.html.twig', [
            'form' => $form,
            'deliveryMethod' => $deliveryMethod
        ]);
    }
}