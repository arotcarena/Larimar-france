<?php 
namespace App\Tests\Functional\Controller\En\Shop;

use App\Entity\Product;
use App\Tests\Utils\FixturesTrait;
use App\Repository\ProductRepository;
use App\Tests\Functional\FunctionalTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use App\DataFixtures\Tests\ProductWithOrWithoutCategoryTestFixtures;

/**
 * @group FunctionalShop
 */
class ProductControllerTest extends FunctionalTest
{
    use FixturesTrait;

    public function setUp(): void
    {
        parent::setUp();

        $this->loadFixtures([ProductWithOrWithoutCategoryTestFixtures::class]);
    }

    //TEST SHOW
    public function testShowRouteWithOnlyProductSlugParam()
    {
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-without-category-or-subcategory']);

        $this->client->request('GET', $this->urlGenerator->generate('en_product_show', [
            'slug' => $product->getEnSlug(),
            'publicRef' => $product->getPublicRef()
        ]));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $product->getEnDesignation());
    }
    public function testShowRouteWithCategorySlug()
    {
        $productWithCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category']);

        $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategory', [
            'slug' => $productWithCategory->getEnSlug(),
            'categorySlug' => $productWithCategory->getCategory()->getEnSlug(),
            'publicRef' => $productWithCategory->getPublicRef()
        ]));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $productWithCategory->getEnDesignation());
    }
    public function testShowRouteWithCategoryAndSubCategorySlug()
    {
        $productWithCategoryAndSubCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);

        $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategoryAndSubCategory', [
            'slug' => $productWithCategoryAndSubCategory->getEnSlug(),
            'categorySlug' => $productWithCategoryAndSubCategory->getCategory()->getEnSlug(),
            'subCategorySlug' => $productWithCategoryAndSubCategory->getSubCategory()->getEnSlug(),
            'publicRef' => $productWithCategoryAndSubCategory->getPublicRef()
        ]));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $productWithCategoryAndSubCategory->getEnDesignation());

    }
    public function testShowMissingRouteParams()
    {
        $productWithCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category']);

        $this->expectException(InvalidParameterException::class);
        $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategory', [
            'slug' => $productWithCategory->getEnSlug(),
            'publicRef' => $productWithCategory->getPublicRef()
        ]));
    }
    public function testShowWithEmptyStringSlugParam()
    {
        $productWithCategoryAndSubCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);

        $this->expectException(InvalidParameterException::class);
        $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategoryAndSubCategory', [
            'slug' => '',
            'categorySlug' => $productWithCategoryAndSubCategory->getCategory()->getEnSlug(),
            'subCategorySlug' => $productWithCategoryAndSubCategory->getSubCategory()->getEnSlug(),
            'publicRef' => $productWithCategoryAndSubCategory->getPublicRef()
        ]));
    }
    public function testShowWithWrongSlugParam()
    {
        $productWithCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category']);

        $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategory', [
            'slug' => $productWithCategory->getEnSlug(),
            'categorySlug' => 'un-slug-de-categorie-incorrect',
            'publicRef' => $productWithCategory->getPublicRef()
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowWithFrSlugParam()
    {
        $productWithCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category']);

        $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategory', [
            'slug' => $productWithCategory->getEnSlug(),
            'categorySlug' => $productWithCategory->getCategory()->getSlug(),
            'publicRef' => $productWithCategory->getPublicRef()
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowWithInexistantPublicRefParam()
    {
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-without-category-or-subcategory']);

        $this->client->request('GET', $this->urlGenerator->generate('en_product_show', [
            'slug' => $product->getEnSlug(),
            'publicRef' => 'inexistantpublicref'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowContainsCorrectBreadCrumb()
    {
        $productWithCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category']);
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategory', [
            'slug' => $productWithCategory->getEnSlug(),
            'categorySlug' => $productWithCategory->getCategory()->getEnSlug(),
            'publicRef' => $productWithCategory->getPublicRef()
        ]));
        $this->assertSelectorTextContains('.breadcrumb-home-link', 'Home');
        $this->assertEquals(
            $this->urlGenerator->generate('en_home'),
            $crawler->filter('.breadcrumb-home-link')->attr('href')
        );
        $this->assertSelectorTextContains('.breadcrumb-link', $productWithCategory->getCategory()->getEnName());
        $this->assertEquals(
            $this->urlGenerator->generate('en_category_show', ['slug' => $productWithCategory->getCategory()->getEnSlug()]),
            $crawler->filter('.breadcrumb-link')->attr('href')
        );
        $this->assertSelectorTextContains('.breadcrumb-item', $productWithCategory->getEnDesignation());

        $productWithCategoryAndSubCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategoryAndSubCategory', [
            'slug' => $productWithCategoryAndSubCategory->getEnSlug(),
            'categorySlug' => $productWithCategoryAndSubCategory->getCategory()->getEnSlug(),
            'subCategorySlug' => $productWithCategoryAndSubCategory->getSubCategory()->getEnSlug(),
            'publicRef' => $productWithCategoryAndSubCategory->getPublicRef()
        ]));
        $this->assertSelectorTextContains('.breadcrumb-home-link', 'Home');
        $this->assertSelectorTextContains('.breadcrumb-link:nth-child(2)', $productWithCategoryAndSubCategory->getCategory()->getEnName());
        $this->assertSelectorTextContains('.breadcrumb-link:nth-child(3)', $productWithCategoryAndSubCategory->getSubCategory()->getEnName());
        $this->assertEquals(
            $this->urlGenerator->generate('en_subCategory_show', [
                'categorySlug' => $productWithCategoryAndSubCategory->getCategory()->getEnSlug(),
                'subCategorySlug' => $productWithCategoryAndSubCategory->getSubCategory()->getEnSlug()
            ]),
            $crawler->filter('.breadcrumb-link:nth-child(3)')->attr('href')
        );
        $this->assertSelectorTextContains('.breadcrumb-item', $productWithCategoryAndSubCategory->getEnDesignation());
    }
    public function testShowAddCountViewToProduct()
    {
        $productWithCategoryAndSubCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $this->client->request('GET', $this->urlGenerator->generate('en_product_show_withCategoryAndSubCategory', [
            'slug' => $productWithCategoryAndSubCategory->getEnSlug(),
            'categorySlug' => $productWithCategoryAndSubCategory->getCategory()->getEnSlug(),
            'subCategorySlug' => $productWithCategoryAndSubCategory->getSubCategory()->getEnSlug(),
            'publicRef' => $productWithCategoryAndSubCategory->getPublicRef()
        ]));
        $this->assertResponseIsSuccessful();
        /** @var Product */
        $productWithCategoryAndSubCategory = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $this->assertEquals(1, $productWithCategoryAndSubCategory->getCountViews());
    }
    //TEST INDEX
    public function testIndexRender()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_product_index'));
        $this->assertResponseIsSuccessful();
    }
    public function testIndexContainsCorrectBreadCrumb()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_product_index'));
        $this->assertSelectorTextContains('.breadcrumb-item', 'Search');
        $this->assertSelectorTextContains('.breadcrumb-home-link', 'Home');
        $this->assertEquals(
            $this->urlGenerator->generate('en_home'),
            $crawler->filter('.breadcrumb-home-link')->attr('href')
        );
    }
}