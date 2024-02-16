<?php
namespace App\Controller\Fr\Api\Delivery;

use App\Config\Countries;
use App\Config\SiteConfig;
use App\Service\CartService;
use App\Service\DestinationAreaResolver;
use App\Repository\DeliveryMethodRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Convertor\Fr\DeliveryMethodToArrayConvertor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiDeliveryMethodController extends AbstractController
{
    public function __construct(
        private DeliveryMethodRepository $deliveryMethodRepository,
        private DestinationAreaResolver $destinationAreaResolver,
        private CartService $cartService,
        private DeliveryMethodToArrayConvertor $deliveryMethodConvertor,
    )
    {

    }

    #[Route('/fr/api/deliveryMethod/choices', name: 'fr_api_deliveryMethod_choices', methods: ['POST'])]
    public function choices(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        
        if(!$data->deliveryAddress)
        {
            return $this->json('', 500);
        }

        $count = $data->count ? $data->count: $this->cartService->count();

        $weight = $count * SiteConfig::ARTICLE_WEIGHT;

        $destinationAreas = $this->destinationAreaResolver->resolve($data->deliveryAddress);
        
        $deliveryMethods = $this->deliveryMethodRepository->findByWeightAndAreaArray($weight, $destinationAreas);


        //CUSTOMS FEES ?
        $customsFeesAlert = null;
        //si la destination est hors de l'UE
        if(!in_array($data->deliveryAddress->iso, Countries::EU_ISO))
        {
            $customsFeesAlert = 'Cette livraison est susceptible d\'occasionner des frais douaniers. Pour en savoir plus, consultez la réglementation en vigueur dans votre pays.';
        }

        return $this->json([
            'deliveryMethods' => $this->deliveryMethodConvertor->convert($deliveryMethods),
            'customsFeesAlert' => $customsFeesAlert
        ]);
    }
}
