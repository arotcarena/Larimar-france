<?php
namespace App\Tests\UnitAndIntegration\Convertor;

use DateTimeImmutable;
use App\Entity\Product;
use PHPUnit\Framework\TestCase;
use App\Convertor\PurchaseLineProductConvertor;

/**
 * @group Convertor
 */
class PurchaseLineProductConvertorTest extends TestCase
{
    public function testConvertReturnCorrectValues()
    {
        $product = (new Product)
                    ->setDesignation('produit pour tester')
                    ->setEnDesignation('product to test')
                    ->setPublicRef('public1234')
                    ->setPrivateRef('private1234')
                    ->setPrice(100)
                    ->setCreatedAt(new DateTimeImmutable())
                    ;
        $purchaseLineProductConvertor = new PurchaseLineProductConvertor;

        $result = $purchaseLineProductConvertor->convert($product);

        $this->assertCount(6, $result);
        $this->assertNull($result['id']);
        $this->assertEquals('produit pour tester', $result['designation']);
        $this->assertEquals('product to test', $result['enDesignation']);
        $this->assertEquals('public1234', $result['publicRef']);
        $this->assertEquals('private1234', $result['privateRef']);
        $this->assertEquals(100, $result['price']);
        $this->assertFalse(isset($result['createdAt']));
    }
}