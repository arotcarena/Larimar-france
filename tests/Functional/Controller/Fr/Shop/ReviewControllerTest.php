<?php
namespace App\Tests\Functional\Controller\Fr\Shop;

use App\Entity\Product;
use App\Entity\Purchase;
use InvalidArgumentException;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use App\Service\ProductShowUrlResolver;
use App\Tests\Functional\FunctionalTest;
use App\Tests\Functional\LoginUserTrait;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\UserBoughtProductVerificator;
use Symfony\Component\HttpFoundation\Response;
use App\Convertor\PurchaseLineProductConvertor;
use App\DataFixtures\Tests\ProductTestFixtures;
use App\DataFixtures\Tests\PurchaseTestFixtures;
use App\DataFixtures\Tests\UserWithNoPurchaseTestFixtures;
use App\Tests\Functional\FrFunctionalTest;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

/**
 * @group FunctionalShop
 */
class ReviewControllerTest extends FrFunctionalTest
{
    use LoginUserTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([PurchaseTestFixtures::class]);  // depends on UserTestFixtures & ProductTestFixtures
    }

    // //auth
    // public function testNotLoggedUserCannotAccess()
    // {
    //     $product = $this->findEntity(ProductRepository::class);
    //     $this->client->request('GET', $this->urlGenerator->generate('fr_review_create', ['productSlug' => $product->getSlug(), 'publicRef' => $product->getPublicRef()]));
    //     $this->assertResponseRedirects($this->urlGenerator->generate('fr_security_login'));
    // }
    // public function testUserCanAccess()
    // {
    //     /** @var Purchase */
    //     $purchase = $this->findEntity(PurchaseRepository::class);
    //     $user = $purchase->getUser();
    //     $productArray = $purchase->getPurchaseLines()->get(0)->getProduct();
    //     $product = $this->findEntity(ProductRepository::class, ['id' => $productArray['id']]);
        
    //     $this->loginUser($user);

    //     $this->client->request('GET', $this->urlGenerator->generate('fr_review_create', ['productSlug' => $product->getSlug(), 'publicRef' => $product->getPublicRef()]));
    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', 'Laisser un avis');
    // }

    // //create
    // public function testCreateWithNoProductParam()
    // {
    //     $this->loginUser();
    //     $this->expectException(MissingMandatoryParametersException::class);
    //     $this->client->request('GET', $this->urlGenerator->generate('fr_review_create'));
    // }
    // public function testCreateWithInexistantProductParam()
    // {
    //     $this->loginUser();
    //     $this->client->request('GET', $this->urlGenerator->generate('fr_review_create', ['productSlug' => 'unslugquinexistepas', 'publicRef' => 'unerefquinexistepas']));
    //     $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    // }
    // public function testCreateWithUserThatDidntBuyAnyProduct()
    // {
    //     $this->loadFixtures([UserWithNoPurchaseTestFixtures::class, ProductTestFixtures::class]);
    //     $user = $this->findEntity(UserRepository::class, ['email' => 'user_with_no_purchase@gmail.com']);

    //     $this->loginUser($user);
    //     $product = $this->findEntity(ProductRepository::class);

    //     $this->client->request('GET', $this->urlGenerator->generate('fr_review_create', ['productSlug' => $product->getSlug(), 'publicRef' => $product->getPublicRef()]));
    //     $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    // }
    // public function testCreateWithUserThatDidntBuyThisProduct()
    // {
    //     $this->loadFixtures([UserWithNoPurchaseTestFixtures::class, ProductTestFixtures::class]);
    //     $user = $this->findEntity(UserRepository::class, ['email' => 'user_with_specific_purchase@gmail.com']);
    //     $this->loginUser($user);
    //     $product = $this->getProductWithDifferentSlug('product-for-specific-purchase-user');

    //     $this->assertFalse((new UserBoughtProductVerificator(new PurchaseLineProductConvertor))->verify($user, $product, 'le test ne sera pas concluant car le user a acheté le product. Il faut changer de user ou product'));

    //     $this->client->request('GET', $this->urlGenerator->generate('fr_review_create', ['productSlug' => $product->getSlug(), 'publicRef' => $product->getPublicRef()]));
    //     $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    // }
    // public function testCreateBreadcrumb()
    // {
    //     $purchase = $this->findEntity(PurchaseRepository::class, ['ref' => 'purchase-with-product-having-category-and-subcategory']);
    //     /** @var Crawler $crawler */
    //     [$product, $crawler] = $this->goToReviewPage($purchase);

    //     //home-link
    //     $this->assertSelectorTextContains('.breadcrumb-home-link', 'Accueil');
    //     $this->assertEquals(
    //         $this->urlGenerator->generate('home'),
    //         $crawler->filter('.breadcrumb-home-link')->attr('href')
    //     );
    //     //category-link
    //     $this->assertSelectorTextContains('.breadcrumb-link:nth-child(2)', $product->getCategory()->getName());
    //     $this->assertEquals(
    //         $this->urlGenerator->generate('fr_category_show', ['slug' => $product->getCategory()->getSlug()]),
    //         $crawler->filter('.breadcrumb-link:nth-child(2)')->attr('href')
    //     );
    //     //subcategory-link
    //     $this->assertSelectorTextContains('.breadcrumb-link:nth-child(3)', $product->getSubCategory()->getName());
    //     $this->assertEquals(
    //         $this->urlGenerator->generate('fr_subCategory_show', [
    //             'categorySlug' => $product->getCategory()->getSlug(),
    //             'subCategorySlug' => $product->getSubCategory()->getSlug()
    //         ]),
    //         $crawler->filter('.breadcrumb-link:nth-child(3)')->attr('href')
    //     );
    //     //product-link
    //     $this->assertSelectorTextContains('.breadcrumb-link:last-child', $product->getDesignation());
    //     /** @var ProductShowUrlResolver */
    //     $productShowUrlResolver = $this->client->getContainer()->get(ProductShowUrlResolver::class);
    //     $this->assertEquals(
    //         $productShowUrlResolver->getUrl($product),
    //         $crawler->filter('.breadcrumb-link:last-child')->attr('href')
    //     );
    // }
    // public function testCreateContainsCorrectProductInfos()
    // {
    //     [$product, $crawler] = $this->goToReviewPage();
    //     $this->assertResponseIsSuccessful();
    //     $this->assertSelectorTextContains('h1', 'Laisser un avis', 'Le titre ne contient pas "Laisser un avis"');
    //     $this->assertSelectorTextContains('h1', $product->getDesignation(), 'Le titre ne contient pas ou pas le bon nom de produit');
    // }
    // public function testCreateSubmitInvalidData()
    // {
    //     [$product, $crawler] = $this->goToReviewPage();
    //     $this->expectException(InvalidArgumentException::class);
    //     $form = $crawler->selectButton('Valider')->form([
    //         'review[fullName]' => 'Jean Paul',
    //         'review[rate]' => '7',
    //         'review[comment]' => 'Très bien ce produit'
    //     ]);
    // }
    // public function testCreateSubmitValidDataRedirects()
    // {
    //     [$product, $crawler] = $this->goToReviewPage();
    //     $form = $crawler->selectButton('Valider')->form($this->createValidReviewData());
    //     $this->client->submit($form);
    //     $this->assertResponseRedirects($this->urlGenerator->generate('home'));
    //     $this->client->followRedirect();
    //     $this->assertSelectorExists('.alert.alert-success');
    // }
    // public function testCreateSubmitValidDataCorrectPersist()
    // {
    //     [$product, $crawler] = $this->goToReviewPage();
    //     $form = $crawler->selectButton('Valider')->form($this->createValidReviewData());
    //     $this->client->submit($form);
    //     $product = $this->findEntity(ProductRepository::class, ['id' => $product->getId()]);
    //     $this->assertEquals(
    //         'Jean Paul',
    //         $product->getReviews()->get(0)->getFullName()
    //     );
    //     $this->assertEquals(
    //         '3',
    //         $product->getReviews()->get(0)->getRate()
    //     );
    //     $this->assertEquals(
    //         'Très bien ce produit',
    //         $product->getReviews()->get(0)->getComment()
    //     );
    // }

    private function goToReviewPage(?Purchase $purchase = null): array
    {
        if($purchase === null)
        {
            /** @var Purchase */
            $purchase = $this->findEntity(PurchaseRepository::class);
        }
        $user = $purchase->getUser();
        $productArray = $purchase->getPurchaseLines()->get(0)->getProduct();
        $product = $this->findEntity(ProductRepository::class, ['id' => $productArray['id']]);
        
        $this->loginUser($user);

        $crawler = $this->client->request('GET', $this->urlGenerator->generate('fr_review_create', ['productSlug' => $product->getSlug(), 'publicRef' => $product->getPublicRef()]));
        $this->assertResponseIsSuccessful();
        return [$product, $crawler];
    }

    private function createValidReviewData()
    {
        return [
            'review[fullName]' => 'Jean Paul',
            'review[rate]' => '3',
            'review[comment]' => 'Très bien ce produit'
        ];
    }

    private function getProductWithDifferentSlug(string $slug):Product
    {
        /** @var EntityManagerInterface */
        $em = $this->client->getContainer()->get(EntityManagerInterface::class);
        return $em->createQueryBuilder('p')
                    ->select('p')
                    ->from('App\Entity\Product', 'p')
                    ->where('p.slug != :slug')
                    ->setParameter('slug', $slug)
                    ->getQuery()
                    ->getResult()[0]
                    ;
    }
}