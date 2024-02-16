<?php
namespace App\Tests\UnitAndIntegration\Service;

use App\Config\SiteConfig;
use App\Service\DestinationAreaResolver;
use PHPUnit\Framework\TestCase;

/**
 * @group Service
 */
class DestinationAreaResolverTest extends TestCase
{
    private DestinationAreaResolver $destinationAreaResolver;

    public function setUp(): void
    {
        $this->destinationAreaResolver = new DestinationAreaResolver;
    }
    public function testResolveFr()
    {
        $deliveryAddress = (object)['iso' => 'FR', 'postcode' => '13300'];
        $area = $this->destinationAreaResolver->resolve($deliveryAddress);
        $this->assertEquals([SiteConfig::AREA_FRANCE], $area);
    }
    public function testResolveZoneAAndES()
    {
        $deliveryAddress = (object)['iso' => 'ES', 'postcode' => '01000'];
        $area = $this->destinationAreaResolver->resolve($deliveryAddress);
        if([SiteConfig::AREA_EUROPE, SiteConfig::AREA_ES_PT] !== $area && [SiteConfig::AREA_ES_PT, SiteConfig::AREA_EUROPE] !== $area)
        {
            $this->fail();
        }
        $this->assertCount(2,$area);
    }
    public function testResolveZoneB()
    {
        $deliveryAddress = (object)['iso' => 'UA', 'postcode' => '01000'];
        $area = $this->destinationAreaResolver->resolve($deliveryAddress);
        $this->assertEquals([SiteConfig::AREA_WORLD_B], $area);
    }
    public function testResolveZoneC()
    {
        $deliveryAddress = (object)['iso' => 'US', 'postcode' => '01000'];
        $area = $this->destinationAreaResolver->resolve($deliveryAddress);
        $this->assertEquals([SiteConfig::AREA_WORLD_C], $area);
    }
    public function testResolveOM1()
    {
        $deliveryAddress = (object)['iso' => 'FR', 'postcode' => '97510'];
        $area = $this->destinationAreaResolver->resolve($deliveryAddress);
        $this->assertEquals([SiteConfig::AREA_FRANCE_OM_1], $area);
    }
    public function testResolveOM2()
    {
        $deliveryAddress = (object)['iso' => 'FR', 'postcode' => '98510'];
        $area = $this->destinationAreaResolver->resolve($deliveryAddress);
        $this->assertEquals([SiteConfig::AREA_FRANCE_OM_2], $area);
    }
}