<?php
namespace App\Tests\Functional\Controller\En\Account;

use App\DataFixtures\Tests\ProductTestFixtures;
use App\DataFixtures\Tests\PurchaseTestFixtures;
use App\DataFixtures\Tests\UserTestFixtures;
use App\Repository\PurchaseRepository;
use App\Tests\Functional\FunctionalTest;
use App\Tests\Functional\LoginUserTrait;
use Symfony\Component\HttpFoundation\Response;

/**
 * @group FunctionalAccount
 */
class PurchaseControllerTest extends FunctionalTest
{
    use LoginUserTrait;


    public function setUp(): void 
    {
        parent::setUp();

        $this->loadFixtures([UserTestFixtures::class, ProductTestFixtures::class, PurchaseTestFixtures::class]);

        
    }

    // auth
    public function testNotLoggedUserCannotAccess()
    {
        //create
        $this->client->request('GET', $this->urlGenerator->generate('en_purchase_create'));
        $this->assertResponseRedirects($this->urlGenerator->generate('en_security_login'));
    }

    //create
    public function testCreateNotAccessibleWithEmptyCart()
    {
        $this->loginUser();
        
        $this->client->request('GET', $this->urlGenerator->generate('en_purchase_create'));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    //paymentSuccess
    public function testPaymentSuccessWithoutUrlParams()
    {
        $this->loginUser();
        
        $purchase = $this->findEntity(PurchaseRepository::class, ['ref' => 'valid_purchase']);
        $this->client->request('GET', $this->urlGenerator->generate('en_purchase_create', ['id' => $purchase->getId()]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // le reste ne peut être testé qu'en endToEnd ( car nécessite validation du paiement par stripe )
    

}