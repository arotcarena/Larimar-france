<?php
namespace App\Convertor\Fr;

use App\Convertor\ConvertorTrait;
use App\Entity\DeliveryMethod;

class DeliveryMethodToArrayConvertor
{
    use ConvertorTrait;

    /**
     * @param DeliveryMethod|DeliveryMethod[] $data
     * @return array
     */
    public function convert($data): array
    {
        return $this->convertOneOrMore($data);
    }
    
    public function convertOne(DeliveryMethod $deliveryMethod): array 
    {
        return [
            'id' => $deliveryMethod->getId(),
            'name' => $deliveryMethod->getName(),
            'mode' => $deliveryMethod->getMode(),
            'deliveryTime' => $deliveryMethod->getDeliveryTime(),
            'price' => $deliveryMethod->getPrice()
        ];
    }
}