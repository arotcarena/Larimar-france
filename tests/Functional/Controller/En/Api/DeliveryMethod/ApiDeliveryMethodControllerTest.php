<?php
namespace App\Tests\Functional\Controller\En\Api\DeliveryMethod;

use App\DataFixtures\Customer\DeliveryMethodFixtures;
use App\Tests\Functional\FunctionalTest;
use Symfony\Component\HttpFoundation\Response;

class ApiDeliveryMethodControllerTest extends FunctionalTest
{
    public function setUp(): void 
    {
        parent::setUp();

        $this->loadFixtures([DeliveryMethodFixtures::class]);
    }

    public function testChoicesWithoutDeliveryAddressParam()
    {
        $this->client->request('POST', $this->urlGenerator->generate('en_api_deliveryMethod_choices'), [], [], [], json_encode([
            'count' => 1
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function testChoicesReturnCorrectChoices()
    {
        $this->client->request('POST', $this->urlGenerator->generate('en_api_deliveryMethod_choices'), [], [], [], json_encode([
            'deliveryAddress' => [
                'iso' => 'NL',
                'postcode' => '00000'
            ],
            'count' => 1
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(4, $data->deliveryMethods);

        $this->client->request('POST', $this->urlGenerator->generate('en_api_deliveryMethod_choices'), [], [], [], json_encode([
            'deliveryAddress' => [
                'iso' => 'NL',
                'postcode' => '00000'
            ],
            'count' => 6
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(3, $data->deliveryMethods);

        $this->client->request('POST', $this->urlGenerator->generate('en_api_deliveryMethod_choices'), [], [], [], json_encode([
            'deliveryAddress' => [
                'iso' => 'FR',
                'postcode' => '97500'
            ],
            'count' => 1
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(3, $data->deliveryMethods);
        
        $this->client->request('POST', $this->urlGenerator->generate('en_api_deliveryMethod_choices'), [], [], [], json_encode([
            'deliveryAddress' => [
                'iso' => 'CH',
                'postcode' => '97500'
            ],
            'count' => 6
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertCount(2, $data->deliveryMethods);
    }

    public function testChoicesCustomsFeesAlertIsNullWhenCountryInEU()
    {
        $this->client->request('POST', $this->urlGenerator->generate('en_api_deliveryMethod_choices'), [], [], [], json_encode([
            'deliveryAddress' => [
                'iso' => 'DE',
                'postcode' => '00000'
            ],
            'count' => 1
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertNull($data->customsFeesAlert);
    }

    public function testChoicesCustomsFeesAlertIsNotNullWhenCountryInEU()
    {
        $this->client->request('POST', $this->urlGenerator->generate('en_api_deliveryMethod_choices'), [], [], [], json_encode([
            'deliveryAddress' => [
                'iso' => 'US',
                'postcode' => '00000'
            ],
            'count' => 1
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertNotNull($data->customsFeesAlert);
    }
}