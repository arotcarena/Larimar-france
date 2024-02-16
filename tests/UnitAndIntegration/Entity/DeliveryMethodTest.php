<?php
namespace App\Tests\UnitAndIntegration\Entity;

use App\Config\SiteConfig;
use App\Entity\DeliveryMethod;
use App\Tests\UnitAndIntegration\Entity\EntityTest;
use DateTime;

/**
 * @group Entity
 */
class DeliveryMethodTest extends EntityTest
{
    public function testInvalidNoPrice()
    {
        $this->assertHasErrors(
            2,
            $this->createNoPriceDeliveryMethod()
        );
    }
    public function testInvalidNegativePrice()
    {
        $this->assertHasErrors(
            1,
            $this->createValidDeliveryMethod()->setPrice(-5)
        );
    }
    public function testInvalidNegativeDeliveryTime()
    {
        $this->assertHasErrors(
            1,
            $this->createValidDeliveryMethod()->setDeliveryTime(-5)
        );
    }
    public function testValid()
    {
        $this->assertHasErrors(0, $this->createValidDeliveryMethod());
    }

    private function createNoPriceDeliveryMethod(): DeliveryMethod
    {
        return (new DeliveryMethod)
                ->setName('colissimo')
                ->setEnName('colissimo')
                ->setMinWeight(250)
                ->setMaxWeight(500)
                ->setDestinationArea(SiteConfig::AREA_FRANCE)
                ->setUpdatedAt(new DateTime())
                ;
    }
    private function createValidDeliveryMethod(): DeliveryMethod
    {
        return (new DeliveryMethod)
                ->setName('colissimo')
                ->setEnName('colissimo')
                ->setMinWeight(250)
                ->setMaxWeight(500)
                ->setDestinationArea(SiteConfig::AREA_FRANCE)
                ->setPrice(500)
                ->setUpdatedAt(new DateTime())
                ;
    }
}