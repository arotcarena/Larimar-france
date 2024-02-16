<?php
namespace App\Tests\EndToEnd\Fr\Checkout;

use App\Service\StripeService;
use App\Tests\EndToEnd\EndToEndTest;
use App\Tests\EndToEnd\Fr\Utils\CartTrait;
use App\Twig\Runtime\PriceFormaterExtensionRuntime;

class ApiPaymentControllerEndToEndTest extends EndToEndTest
{
    use CartTrait;

    private StripeService $stripeService;

    private PriceFormaterExtensionRuntime $priceFormater;

    public function setUp(): void 
    {
        parent::setUp();

        $this->stripeService = static::getContainer()->get(StripeService::class);

        $this->priceFormater = static::getContainer()->get(PriceFormaterExtensionRuntime::class);
    }

    //on est obligé de faire ce test en endToEnd car en Functional la session ne fonctionne pas, donc cart vide
    public function testCreatePaymentIntentReturnCorrectClientSecretAmountCorrespondingCartTotalPriceIfNoAmountIsPassed()
    {
        $this->addProduct('obj');
        $this->addProduct('prod');
        $this->assertCartCount('2');

        $totalPrice = $this->getCartTotalPrice();
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('fr_api_payment_createPaymentIntent'));
        $data = json_decode($crawler->getText());
        $paymentIntent = $this->stripeService->retrievePaymentIntent($data->clientSecret);

        $this->assertEquals(
            $totalPrice,
            $this->priceFormater->format($paymentIntent->amount)
        );
        $this->emptyCart();
    }

    
}