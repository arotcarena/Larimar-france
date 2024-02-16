<?php
namespace App\Tests\UnitAndIntegration\Repository;

use App\Config\SiteConfig;
use App\DataFixtures\Customer\DeliveryMethodFixtures;
use App\Repository\DeliveryMethodRepository;
use App\Tests\Utils\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group Repository
 */
class DeliveryMethodRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    private DeliveryMethodRepository $deliveryMethodRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([DeliveryMethodFixtures::class]);

        $this->deliveryMethodRepository = static::getContainer()->get(DeliveryMethodRepository::class);
    }

    public function testFindByWeightAndAreaArrayWithInexistantAreas()
    {
        $this->assertCount(
            0,
            $this->deliveryMethodRepository->findByWeightAndAreaArray(100, ['inexistant_area', 'other_inexistant_area'])
        );
    }

    public function testFindByWeightAndAreaArrayWithFrAreaAndLessThan250gWeight()
    {
        $this->assertCount(
            4,
            $this->deliveryMethodRepository->findByWeightAndAreaArray(220, [SiteConfig::AREA_FRANCE])
        );
    }

    public function testFindByWeightAndAreaArrayWithFrOMAreaAndMoreThan250gWeight()
    {
        $this->assertCount(
            2,
            $this->deliveryMethodRepository->findByWeightAndAreaArray(260, [SiteConfig::AREA_FRANCE_OM_1])
        );
    }

    public function testFindByWeightAndAreaArrayWithWorldBAreaAndLessThan250gWeight()
    {
        $this->assertCount(
            3,
            $this->deliveryMethodRepository->findByWeightAndAreaArray(150, [SiteConfig::AREA_WORLD_B])
        );
    }

}