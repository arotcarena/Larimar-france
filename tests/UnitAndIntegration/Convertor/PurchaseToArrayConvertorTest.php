<?php
namespace App\Tests\UnitAndIntegration\Convertor;

use App\Config\SiteConfig;
use PHPUnit\Framework\TestCase;
use App\Convertor\AddressToArrayConvertor;
use App\Convertor\PurchaseToArrayConvertor;
use App\Entity\PostalDetail;
use App\Entity\Purchase;
use App\Entity\PurchaseLine;
use PHPUnit\Framework\MockObject\MockObject;
use App\Twig\Runtime\PriceFormaterExtensionRuntime;
use DateTimeImmutable;

class PurchaseToArrayConvertorTest extends TestCase
{
    private MockObject|AddressToArrayConvertor $addressConvertor;

    private PurchaseToArrayConvertor $purchaseConvertor;


    public function setUp(): void 
    {
        $this->addressConvertor = $this->createMock(AddressToArrayConvertor::class);
        
        $this->purchaseConvertor = new PurchaseToArrayConvertor($this->addressConvertor, new PriceFormaterExtensionRuntime);
    }


    //light
    public function testLightContainsCorrectPropertiesWhenConvertOne()
    {
        $purchase = $this->createPurchase();
        $this->assertEquals([
            'id', 'ref', 'status', 'total', 'createdAt'
        ], array_keys($this->purchaseConvertor->convert($purchase, 'light')));

    }
    
    public function testLightContainsCorrectPropertiesWhenConvertMore()
    {
        $purchases = [$this->createPurchase(), $this->createPurchase()];
        $this->assertEquals([
            'id', 'ref', 'status', 'total', 'createdAt'
        ], array_keys($this->purchaseConvertor->convert($purchases, 'light')[0]));
    }

    public function testLightContainsCorrectValues()
    {
        $purchase = $this->createPurchase();
        $returnPurchase = $this->purchaseConvertor->convert($purchase, 'light');
        $this->assertEquals(null, $returnPurchase['id']);
        $this->assertEquals('ref', $returnPurchase['ref']);
        $this->assertEquals(SiteConfig::STATUS_LABELS[SiteConfig::STATUS_DELIVERED], $returnPurchase['status']);
        $this->assertEquals(150, $returnPurchase['total']);
        $this->assertEquals($purchase->getCreatedAt()->format('d/m/Y H:i'), $returnPurchase['createdAt']);
    }


    //full
    public function testFullContainsCorrectPropertiesWhenConvertOne()
    {
        $purchase = $this->createPurchase();
        $this->assertEquals([
            'id', 'ref', 'status', 'totalPrice', 'createdAt', 'tracking', 'deliveryDetail', 'invoiceDetail', 'purchaseLines', 'shippingInfo'
        ], array_keys($this->purchaseConvertor->convert($purchase, 'full')));

    }
    
    public function testFullContainsCorrectPropertiesWhenConvertMore()
    {
        $purchases = [$this->createPurchase(), $this->createPurchase()];
        $this->assertEquals([
            'id', 'ref', 'status', 'totalPrice', 'createdAt', 'tracking', 'deliveryDetail', 'invoiceDetail', 'purchaseLines', 'shippingInfo'
        ], array_keys($this->purchaseConvertor->convert($purchases, 'full')[0]));
    }

    public function testFullShippingInfoContainsCorrectKeys()
    {
        $purchases = [$this->createPurchase(), $this->createPurchase()];
        $shippingInfo = $this->purchaseConvertor->convert($purchases, 'full')[0]['shippingInfo'];
        $this->assertArrayHasKey('name', $shippingInfo);
        $this->assertArrayHasKey('mode', $shippingInfo);
        $this->assertArrayHasKey('relay', $shippingInfo);
        $this->assertArrayHasKey('price', $shippingInfo);
    }

    public function testFullContainsCorrectValues()
    {
        $purchase = $this->createPurchase();
        $returnPurchase = $this->purchaseConvertor->convert($purchase, 'full');
        $this->assertEquals(null, $returnPurchase['id']);
        $this->assertEquals('ref', $returnPurchase['ref']);
        $this->assertEquals(SiteConfig::STATUS_LABELS[SiteConfig::STATUS_DELIVERED], $returnPurchase['status']);
        $this->assertEquals(100, $returnPurchase['totalPrice']);
        $this->assertEquals(50, $returnPurchase['shippingInfo']['price']);
        $this->assertEquals(SiteConfig::DELIVERY_MODE_HOME, $returnPurchase['shippingInfo']['mode']);
        $this->assertNull($returnPurchase['shippingInfo']['relay']);
        $this->assertEquals($purchase->getCreatedAt()->format('d/m/Y H:i'), $returnPurchase['createdAt']);
        $this->assertEquals('tracking', $returnPurchase['tracking']);
    }

    /**
     * impossible de tester chaque postalDetail séparément (problème mock expectations on ne peut plus précisé l'ordre d'appel avec des params différents)
     *
     * @return void
     */
    public function testFullContainsCorrectPostalDetails()
    {
        $purchase = $this->createPurchase();
        $this->addressConvertor->expects($this->exactly(2))
                                ->method('convert')
                                ->with($purchase->getDeliveryDetail())  // invoiceDetail === deliveryDetail
                                ->willReturn(['test_array'])
                                ;
        $returnPurchase = $this->purchaseConvertor->convert($purchase, 'full');
        $this->assertEquals(['test_array'], $returnPurchase['deliveryDetail']);
        $this->assertEquals(['test_array'], $returnPurchase['invoiceDetail']);
    }

    public function testFullContainsCorrectPurchaseLines()
    {
        $purchase = $this->createPurchase();
        $returnPurchase = $this->purchaseConvertor->convert($purchase, 'full');

        $this->assertEquals(
            1, $returnPurchase['purchaseLines'][0]['product']['id']
        );
        $this->assertEquals(
            2, $returnPurchase['purchaseLines'][1]['product']['id']
        );

        $this->assertEquals(
            10, $returnPurchase['purchaseLines'][0]['product']['price']
        );
        $this->assertEquals(
            80, $returnPurchase['purchaseLines'][1]['product']['price']
        );

        $this->assertEquals(
            2, $returnPurchase['purchaseLines'][0]['quantity']
        );
        $this->assertEquals(
            1, $returnPurchase['purchaseLines'][1]['quantity']
        );

        $this->assertEquals(
            20, $returnPurchase['purchaseLines'][0]['totalPrice']
        );
        $this->assertEquals(
            80, $returnPurchase['purchaseLines'][1]['totalPrice']
        );
    }


    private function createPurchase(): Purchase
    {
        $postalDetail = (new PostalDetail)
                    ->setCivility('civility')
                    ->setFirstName('firstName')
                    ->setLastName('lastName')
                    ->setLineOne('lineOne')
                    ->setLineTwo('lineTwo')
                    ->setPostcode('postcode')
                    ->setCity('city')
                    ->setCountry('country')
                    ->setIso('FR')
                    ->setContinents([])
                    ;

        return (new Purchase)
                ->setRef('ref')
                ->setStatus(SiteConfig::STATUS_DELIVERED)
                ->setTotalPrice(100)
                ->setCreatedAt(new DateTimeImmutable())
                ->setTracking('tracking')
                ->setShippingInfo([
                    'mode' => SiteConfig::DELIVERY_MODE_HOME,
                    'name' => 'Colissimo',
                    'relay' => null,
                    'price' => 50
                ])                
                ->setDeliveryDetail($postalDetail)
                ->setInvoiceDetail($postalDetail)
                ->addPurchaseLine(
                    (new PurchaseLine)
                    ->setProduct([
                        'id' => 1,
                        'publicRef' => 'publicRef1',
                        'privateRef' => 'privateRef1',
                        'designation' => 'designation1',
                        'enDesignation' => 'enDesignation1',
                        'price' => 10
                    ])
                    ->setQuantity(2)
                    ->setTotalPrice(20)
                )
                ->addPurchaseLine(
                    (new PurchaseLine)
                    ->setProduct([
                        'id' => 2,
                        'publicRef' => 'publicRef2',
                        'privateRef' => 'privateRef2',
                        'designation' => 'designation2',
                        'enDesignation' => 'enDesignation2',
                        'price' => 80
                    ])
                    ->setQuantity(1)
                    ->setTotalPrice(80)
                )
                ;
    }

}