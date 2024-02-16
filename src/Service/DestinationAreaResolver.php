<?php
namespace App\Service;

use App\Config\Countries;
use App\Config\SiteConfig;
use stdClass;

class DestinationAreaResolver 
{
    public function resolve(stdClass $deliveryAddress): array
    {
        //la poste et mondial relay
        if($deliveryAddress->iso === 'FR')
        {
            //si OM => la poste
            if(str_starts_with($deliveryAddress->postcode, Countries::FRANCE_DPT_OM_1))
            {
                return [SiteConfig::AREA_FRANCE_OM_1];
            }
            if(str_starts_with($deliveryAddress->postcode, Countries::FRANCE_DPT_OM_2))
            {
                return [SiteConfig::AREA_FRANCE_OM_2];
            }
            return [SiteConfig::AREA_FRANCE];
        }

        //mondial relay world
        $areas = [];
        if(in_array($deliveryAddress->iso, ['BE', 'LU']))
        {
            $areas[] = SiteConfig::AREA_BE_LU;
        }
        if($deliveryAddress->iso === 'NL')
        {
            $areas[] = SiteConfig::AREA_NL;
        }
        if(in_array($deliveryAddress->iso, ['ES', 'PT']))
        {
            $areas[] = SiteConfig::AREA_ES_PT;
        }

        //la poste
        if(in_array($deliveryAddress->iso, Countries::EUROPE_ISO))
        {
            $areas[] = SiteConfig::AREA_EUROPE;
        }
        elseif(in_array($deliveryAddress->iso, Countries::WORLD_B_ISO))
        {
            $areas[] = SiteConfig::AREA_WORLD_B;
        }
        else
        {
            $areas[] = SiteConfig::AREA_WORLD_C;
        }

        return $areas;
    }
}