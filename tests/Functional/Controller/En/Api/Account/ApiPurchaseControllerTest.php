<?php
namespace App\Tests\Functional\Controller\En\Api\Account;

use stdClass;
use Exception;
use Stripe\Stripe;
use App\Entity\Cart;
use App\Entity\User;
use App\Entity\Purchase;
use Stripe\PaymentIntent;
use App\Config\SiteConfig;
use App\Config\TextConfig;
use App\Config\SecurityConfig;
use App\Repository\UserRepository;
use App\Repository\PurchaseRepository;
use App\Service\ShippingCostCalculator;
use App\Tests\Functional\FunctionalTest;
use App\Tests\Functional\LoginUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\DataFixtures\Tests\CartTestFixtures;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\Tests\PurchaseTestFixtures;
use App\DataFixtures\Tests\UserPurchaseTestFixtures;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

/**
 * @group FunctionalApi
 */
class ApiPurchaseControllerTest extends FunctionalTest
{
    use LoginUserTrait;

    private ShippingCostCalculator $shippingCostCalculator;

    private EntityManagerInterface $em;

    private ?Cart $cart;

    private User $user;

    public function setUp(): void 
    {
        parent::setUp();

        $this->loadFixtures([CartTestFixtures::class, PurchaseTestFixtures::class]); // depends on UserTestFixtures & ProductTestFixtures

        /** @var User */
        $user = $this->findEntity(UserRepository::class, ['email' => 'confirmed_user@gmail.com']);  // user avec cart avec 2 cartLines
        $this->loginUser($user);
        $this->user = $user;

        $this->cart = $user->getCart();

        $this->shippingCostCalculator = $this->client->getContainer()->get(ShippingCostCalculator::class);
    
        $this->em = $this->client->getContainer()->get(EntityManagerInterface::class);
    }

    //auth
    // on ne teste pas auth ici car ça n'a pas vraiment d'importance


    public function testFindPaginatedLightReturnOnlyPurchasesThatBelongToCurrentUser()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_api_purchase_findPaginatedLight'));
        $data = json_decode($this->client->getResponse()->getContent());
        $i = 0;
        foreach($data->purchases as $purchase)
        {
            $i++;
            if($i > 5)
            {
                return;
            }
            $ref = $purchase->ref;
            $dbPurchase = $this->findEntity(PurchaseRepository::class, ['ref' => $ref]);
            $this->assertEquals($this->user->getId(), $dbPurchase->getUser()->getId());
        }
    }

    public function testFindPaginatedCountReturnEqualsNumberOfPurchases()
    {
        $this->assertTrue($this->user->getPurchases()->count() > 0, 'le test n\'est pas probant car le user n\'a aucune purchase');
        $this->client->request('GET', $this->urlGenerator->generate('en_api_purchase_findPaginatedLight'));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertCount($data->count, $data->purchases);
    }

    public function testFindPaginatedReturnCorrectCount()
    {
        $this->assertTrue($this->user->getPurchases()->count() > 0, 'le test n\'est pas probant car le user n\'a aucune purchase');
        $this->client->request('GET', $this->urlGenerator->generate('en_api_purchase_findPaginatedLight'));
        $data = json_decode($this->client->getResponse()->getContent());

        $count = 0;
        foreach($this->user->getPurchases() as $purchase)
        {
            if($purchase->getStatus() !== SiteConfig::STATUS_PENDING) 
            {
                $count++;
            }
        }
        $this->assertEquals($count, $data->count);
    }

    public function testFindPaginatedPurchasesContainsCorrectProperties()
    {
        $this->assertTrue($this->user->getPurchases()->count() > 0, 'le test n\'est pas probant car le user n\'a aucune purchase');
        $this->client->request('GET', $this->urlGenerator->generate('en_api_purchase_findPaginatedLight'));
        $purchase = json_decode($this->client->getResponse()->getContent())->purchases[0];
        $this->assertEquals([
            'id', 'ref', 'status', 'total', 'createdAt'
        ], array_keys(get_object_vars($purchase)));        
    }
    
    public function testFindPaginatedLightDontReturnPendingPurchases()
    {
        $this->loadFixtures([UserPurchaseTestFixtures::class]);
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_having_one_purchase_pending@gmail.com']);
        $this->client->loginUser($user);
        $this->client->request('GET', $this->urlGenerator->generate('en_api_purchase_findPaginatedLight'));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals(0, $data->count);
    }

    //findOneFull
    public function testFindOneFullWithNoIdParam()
    {
        $this->expectException(MissingMandatoryParametersException::class);
        $this->client->request('GET', $this->urlGenerator->generate('en_api_purchase_findOneFull'));
    }
    public function testFindOneFullWithInvalidIdParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_api_purchase_findOneFull', [
            'id' => 14152458545
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testFindOneFullContainsCorrectProperties()
    {
        $this->assertTrue($this->user->getPurchases()->count() > 0, 'le test n\'est pas probant car le user n\'a aucune purchase');
        $purchase = $this->user->getPurchases()->get(0);
        $this->client->request('GET', $this->urlGenerator->generate('en_api_purchase_findOneFull', [
            'id' => $purchase->getId()
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals([
            'id', 'ref', 'status', 'totalPrice', 'createdAt', 'tracking', 'deliveryDetail', 'invoiceDetail', 'purchaseLines', 'shippingInfo'
        ], array_keys(get_object_vars($data)));
    }


    //lastVerificationBeforePayment
    // en functional le cart est toujours vide car la session ne fonctionne pas
    public function testLastVerificationBeforePaymentWithEmptyCart()  // ex: si l'admin vient de supprimer le seul produit dans notre cart
    {
        $purchase = $this->createAndPersistEmptyPurchase();
        
        $paymentIntent = $this->createPaymentIntent(100, $purchase->getId());
        $this->client->request('POST', $this->urlGenerator->generate('en_api_purchase_lastVerificationBeforePayment', [
            'id' => $purchase->getId()
        ]), [], [], [], json_encode(['piSecret' => $paymentIntent->client_secret, 'checkoutData' => $this->createValidCheckoutData()]));

        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals(
            $this->urlGenerator->generate('en_home'),
            $data->errors->target
        );
    }
    
   
    public function testLastVerificationBeforePaymentDatabaseQueriesCount()
    {
        $purchase = $this->createAndPersistEmptyPurchase();

        $this->client->enableProfiler();
        
        $paymentIntent = $this->createPaymentIntent(100, $purchase->getId());
        $this->client->request('POST', $this->urlGenerator->generate('en_api_purchase_lastVerificationBeforePayment', [
            'id' => $purchase->getId()
        ]), [], [], [], json_encode(['piSecret' => $paymentIntent->client_secret, 'checkoutData' => $this->createValidCheckoutData()]));

        /** @var DoctrineDataCollector */
        $dbCollector = $this->client->getProfile()->getCollector('db');
        $this->assertLessThan(20, $dbCollector->getQueryCount(), 'dans mon premier test on était à 14');
    }
    

    private function createAndPersistEmptyPurchase(): Purchase
    {
        $purchase = new Purchase;
        $this->em->persist($purchase);
        $this->em->flush();
        return $purchase;
    }

    private function createPaymentIntent(int $amount, int $purchaseId): PaymentIntent
    {
        Stripe::setApiKey(SecurityConfig::STRIPE_SECRET_KEY);
        return PaymentIntent::create([
            'amount' => $amount,  
            'currency' => 'eur',
            'metadata' => [
                'purchaseId' => $purchaseId 
            ],
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);
    }

    private function createValidCheckoutData(): stdClass
    {
        return (object)[
            'civilState' => (object)[
                'civility' => TextConfig::CIVILITY_M,
                'firstName' => 'civility_firstName',
                'lastName' => 'civility_lastName',
            ],
            'deliveryAddress' => (object)[
                'civility' => TextConfig::CIVILITY_M,
                'firstName' => 'delivery_firstName',
                'lastName' => 'delivery_lastName',
                'lineOne' => 'delivery_lineOne',
                'lineTwo' => 'delivery_lineTwo',
                'postcode' => '75000',
                'city' => 'delivery_city',
                'country' => 'delivery_country',
            ],
            'invoiceAddress' => (object)[
                'lineOne' => 'invoice_lineOne',
                'lineTwo' => 'invoice_lineTwo',
                'postcode' => '75000',
                'city' => 'invoice_city',
                'country' => 'invoice_country',
            ],
        ];
    }
  
}