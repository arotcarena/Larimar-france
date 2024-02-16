<?php
namespace App\DataFixtures\Customer;

use App\Config\SiteConfig;
use App\Entity\DeliveryMethod;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;



class DeliveryMethodFixtures extends Fixture implements FixtureGroupInterface
{
    public const MONDIAL_RELAY_WEIGHT_RANGE = [
        0 => 500,
        501 => 1000,
        1001 => 2000,
        2001 => 3000,
        3001 => 4000,
        4001 => 5000,
        5001 => 7000,
        7001 => 10000,
        10001 => 15000,
        15001 => 20000,
        20001 => 30000
    ];

    public const COLISSIMO_FR_WEIGHT_RANGE = [
        0 => 250,
        251 => 500,
        501 => 750,
        751 => 1000,
        1001 => 2000,
        2001 => 5000,
        5001 => 10000,
        10001 => 15000,
        15001 => 30000
    ];

    public const COLISSIMO_OVERSEA_AND_EUROPE_WEIGHT_RANGE = [
        0 => 500,
        501 => 1000,
        1001 => 2000,
        2001 => 5000,
        5001 => 10000,
        10001 => 15000,
        15001 => 30000
    ];

    public const COLISSIMO_WORLD_WEIGHT_RANGE = [
        0 => 500,
        501 => 1000,
        1001 => 2000,
        2001 => 5000,
        5001 => 10000,
        10001 => 15000,
        15001 => 20000
    ];

    public const FOLLOW_LETTER_WEIGHT_RANGE = [
        0 => 20,
        21 => 100,
        101 => 250
    ];

    public function load(ObjectManager $manager)
    {
        /**LETTRE SUIVIE */
        foreach(self::FOLLOW_LETTER_WEIGHT_RANGE as $minWeight => $maxWeight)
        {
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Lettre suivie')
                                ->setEnName('Tracked letter')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Lettre suivie')
                                ->setEnName('Tracked letter')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE_OM_1)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Lettre suivie')
                                ->setEnName('Tracked letter')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE_OM_2)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Lettre suivie')
                                ->setEnName('Tracked letter')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_EUROPE)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
            
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Lettre suivie')
                                ->setEnName('Tracked letter')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_WORLD_C)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Lettre suivie')
                                ->setEnName('Tracked letter')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_WORLD_B)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
        }
        /**COLISSIMO */
        /**colissimo world */
        foreach(self::COLISSIMO_WORLD_WEIGHT_RANGE as $minWeight => $maxWeight)
        {
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo')
                                ->setEnName('Colissimo')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_WORLD_B)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
            
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo')
                                ->setEnName('Colissimo')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_WORLD_C)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
        }
        /**colissimo oversea and europe */
        foreach(self::COLISSIMO_OVERSEA_AND_EUROPE_WEIGHT_RANGE as $minWeight => $maxWeight)
        {
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo')
                                ->setEnName('Colissimo')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE_OM_1)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo')
                                ->setEnName('Colissimo')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE_OM_2)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo')
                                ->setEnName('Colissimo')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_EUROPE)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
        }
        /**colissimo fr */
        foreach(self::COLISSIMO_FR_WEIGHT_RANGE as $minWeight => $maxWeight)
        {
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo')
                                ->setEnName('Colissimo')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE)
                                ->setPrice(random_int(400, 2000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
        }


         /**COLISSIMO A DOMICILE contre SIGNATURE */
        /**colissimo world */
        foreach(self::COLISSIMO_WORLD_WEIGHT_RANGE as $minWeight => $maxWeight)
        {
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo contre signature')
                                ->setEnName('Colissimo with signature')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_WORLD_B)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
            
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo contre signature')
                                ->setEnName('Colissimo with signature')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_WORLD_C)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
        }
        /**colissimo oversea and europe */
        foreach(self::COLISSIMO_OVERSEA_AND_EUROPE_WEIGHT_RANGE as $minWeight => $maxWeight)
        {
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo contre signature')
                                ->setEnName('Colissimo with signature')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE_OM_1)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo contre signature')
                                ->setEnName('Colissimo with signature')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE_OM_2)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo contre signature')
                                ->setEnName('Colissimo with signature')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_EUROPE)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
        }
        /**colissimo fr */
        foreach(self::COLISSIMO_FR_WEIGHT_RANGE as $minWeight => $maxWeight)
        {
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Colissimo contre signature')
                                ->setEnName('Colissimo with signature')
                                ->setMode(SiteConfig::DELIVERY_MODE_HOME)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE)
                                ->setPrice(random_int(400, 2000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
        }

        /**MONDIAL RELAY */
        /** point relais */
        foreach(self::MONDIAL_RELAY_WEIGHT_RANGE as $minWeight => $maxWeight)
        {
            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Mondial Relay')
                                ->setEnName('Mondial Relay')
                                ->setMode(SiteConfig::DELIVERY_MODE_RELAY)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_FRANCE)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Mondial Relay')
                                ->setEnName('Mondial Relay')
                                ->setMode(SiteConfig::DELIVERY_MODE_RELAY)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_BE_LU)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Mondial Relay')
                                ->setEnName('Mondial Relay')
                                ->setMode(SiteConfig::DELIVERY_MODE_RELAY)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_NL)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);

            $deliveryMethod = (new DeliveryMethod)
                                ->setName('Mondial Relay')
                                ->setEnName('Mondial Relay')
                                ->setMode(SiteConfig::DELIVERY_MODE_RELAY)
                                ->setDeliveryTime(random_int(1, 4))
                                ->setMinWeight($minWeight)
                                ->setMaxWeight($maxWeight)
                                ->setDestinationArea(SiteConfig::AREA_ES_PT)
                                ->setPrice(random_int(400, 3000))
                                ->setUpdatedAt(new DateTime())
                                ;
            $manager->persist($deliveryMethod);
        }


        $manager->flush();
    }


    public static function getGroups(): array
    {
        return ['dev'];
    }
}