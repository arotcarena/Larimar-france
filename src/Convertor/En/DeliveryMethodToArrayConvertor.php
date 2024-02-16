<?php
namespace App\Convertor\En;

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
            'name' => $deliveryMethod->getEnName(),
            'mode' => $deliveryMethod->getMode(),
            'deliveryTime' => $deliveryMethod->getDeliveryTime(),
            'price' => $deliveryMethod->getPrice()
        ];
    }
}