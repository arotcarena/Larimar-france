<?php
namespace App\Tests\Functional\Controller\Fr\Api\Payment;

use App\Config\SiteConfig;
use App\Service\StripeService;
use App\Repository\CartRepository;
use App\Repository\PurchaseRepository;
use App\Tests\Functional\FrFunctionalTest;
use App\DataFixtures\Tests\CartTestFixtures;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\Tests\PurchaseTestFixtures;

/**
 * @group FunctionalApi
 */
class ApiPaymentControllerTest extends FrFunctionalTest
{
    private StripeService $stripeService;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([PurchaseTestFixtures::class, CartTestFixtures::class]);

        $this->stripeService = $this->client->getContainer()->get(StripeService::class);
    }

    public function testCreatePaymentIntentWithAmountZeroAsParam()
    {
        $this->client->request('POST', $this->urlGenerator->generate('fr_api_payment_createPaymentIntent'), [], [], [], json_encode([
            'totalPrice' => 0,
            'shippingCost' => 450
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testCreatePaymentIntentReturnCorrectClientSecretAmountIfAmountIsPassed()
    {
        $this->client->request('POST', $this->urlGenerator->generate('fr_api_payment_createPaymentIntent'), [], [], [], json_encode([
            'totalPrice' => 3000,
            'shippingCost' => 450
        ]));
        $this->assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent());
        $paymentIntent = $this->stripeService->retrievePaymentIntent($data->clientSecret);

        $this->assertEquals(
            3450,
            $paymentIntent->amount
        );
    }
    
    public function testCreatePaymentIntentCreateEmptyPurchaseAndReturnHisIdInPaymentIntentMetadata()
    {
        $this->client->request('POST', $this->urlGenerator->generate('fr_api_payment_createPaymentIntent'), [], [], [], json_encode([
            'totalPrice' => 2000,
            'shippingCost' => 450
        ]));
        $this->assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent());
        $paymentIntent = $this->stripeService->retrievePaymentIntent($data->clientSecret);

        $id = $paymentIntent->metadata->purchaseId;
        $purchase = $this->findEntity(PurchaseRepository::class, ['id' => $id]);
        $this->assertNotNull($purchase, 'L\'id retourné ne correspond à aucune purchase');
        $this->assertNull($purchase->getRef(), 'La purchase devrait être vide mais elle a une Ref');
    }

}