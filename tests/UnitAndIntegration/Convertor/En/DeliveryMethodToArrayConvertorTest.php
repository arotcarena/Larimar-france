<?php
namespace App\Tests\UnitAndIntegration\Convertor\En;

use App\Convertor\En\DeliveryMethodToArrayConvertor;
use App\DataFixtures\Customer\DeliveryMethodFixtures;
use App\Repository\DeliveryMethodRepository;
use App\Tests\Utils\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group Convertor
 */
class DeliveryMethodToArrayConvertorTest extends KernelTestCase
{
    use FixturesTrait;

    private DeliveryMethodToArrayConvertor $deliveryMethodConvertor;

    private DeliveryMethodRepository $deliveryMethodRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->deliveryMethodConvertor = new DeliveryMethodToArrayConvertor;

        $this->deliveryMethodRepository = static::getContainer()->get(DeliveryMethodRepository::class);

        $this->loadFixtures([DeliveryMethodFixtures::class]);
    }

    public function testContainsCorrectKeysWhenConvertOne()
    {
        $deliveryMethod = $this->findEntity(DeliveryMethodRepository::class);
        $returnDeliveryMethod = $this->deliveryMethodConvertor->convert($deliveryMethod);

        $this->assertEquals(
            ['id', 'name', 'mode', 'deliveryTime', 'price'], 
            array_keys($returnDeliveryMethod)
        );
    }

    public function testContainsCorrectKeysWhenConvertAll()
    {
        $deliveryMethods = $this->deliveryMethodRepository->findAll();
        $returnDeliveryMethod = $this->deliveryMethodConvertor->convert($deliveryMethods)[0];

        $this->assertEquals(
            ['id', 'name', 'mode', 'deliveryTime', 'price'], 
            array_keys($returnDeliveryMethod)
        );
    }
  
    public function testReturnCorrectProductsCount()
    {
        $deliveryMethods = $this->deliveryMethodRepository->findAll();
        $data = $this->deliveryMethodConvertor->convert($deliveryMethods);

        $this->assertCount(
            count($deliveryMethods), 
            $data   
        );
    }

    public function testContainsCorrectId()
    {
        $deliveryMethod = $this->findEntity(DeliveryMethodRepository::class);
        $returnDeliveryMethod = $this->deliveryMethodConvertor->convert($deliveryMethod);
        $this->assertEquals(
            $deliveryMethod->getId(),
            $returnDeliveryMethod['id']
        );
    }

    public function testContainsCorrectName()
    {
        $deliveryMethod = $this->findEntity(DeliveryMethodRepository::class);
        $returnDeliveryMethod = $this->deliveryMethodConvertor->convert($deliveryMethod);
        $this->assertEquals(
            $deliveryMethod->getEnName(),
            $returnDeliveryMethod['name']
        );
    }

    public function testContainsCorrectMode()
    {
        $deliveryMethod = $this->findEntity(DeliveryMethodRepository::class);
        $returnDeliveryMethod = $this->deliveryMethodConvertor->convert($deliveryMethod);
        $this->assertEquals(
            $deliveryMethod->getMode(),
            $returnDeliveryMethod['mode']
        );
    }

    public function testContainsCorrectDeliveryTime()
    {
        $deliveryMethod = $this->findEntity(DeliveryMethodRepository::class);
        $returnDeliveryMethod = $this->deliveryMethodConvertor->convert($deliveryMethod);
        $this->assertEquals(
            $deliveryMethod->getDeliveryTime(),
            $returnDeliveryMethod['deliveryTime']
        );
    }

    public function testContainsCorrectPrice()
    {
        $deliveryMethod = $this->findEntity(DeliveryMethodRepository::class);
        $returnDeliveryMethod = $this->deliveryMethodConvertor->convert($deliveryMethod);
        $this->assertEquals(
            $deliveryMethod->getPrice(),
            $returnDeliveryMethod['price']
        );
    }
}