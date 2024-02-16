<?php
namespace App\Convertor;

use App\Entity\Address;
use App\Entity\PostalDetail;
use App\Convertor\ConvertorTrait;
use App\Convertor\ShopConvertorTrait;
use App\Translator\CivilityTranslator;

class AddressToArrayConvertor
{
    use ConvertorTrait;
    use ShopConvertorTrait;


    /**
     * @param Address[]|PostalDetail[]|PostalDetail|Address $data
     * @return array
     */
    public function convert($data): array 
    {
        return $this->convertOneOrMore($data);
    }

    /**
     * Undocumented function
     *
     * @param Address|PostalDetail $address
     * @return array
     */
    private function convertOne($address): array 
    {
        return [
            'id' => $address->getId(),
            'civility' => $address->getCivility(),
            'firstName' => $address->getFirstName(),
            'lastName' => $address->getLastName(),
            'lineOne' => $address->getLineOne(),
            'lineTwo' => $address->getLineTwo() ?: '',
            'postcode' => $address->getPostcode(),
            'city' => $address->getCity(),
            'country' => $address->getCountry(),
            'enCountry' => $address->getEnCountry(),
            'iso' => $address->getIso(),
            'continents' => $address->getContinents()
        ];
    }  
    
}