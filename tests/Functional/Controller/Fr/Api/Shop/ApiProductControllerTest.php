<?php
namespace App\Tests\Functional\Controller\Fr\Api\Shop;

use App\Form\DataModel\SearchParams;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Convertor\Fr\ProductToArrayConvertor;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\Tests\ProductTestFixtures;
use App\DataFixtures\Tests\ProductWithOrWithoutCategoryTestFixtures;
use App\Tests\Functional\FrFunctionalTest;
use Doctrine\Bundle\DoctrineBundle\DataCollector\DoctrineDataCollector;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;



/**
 * @group FunctionalApi
 */
class ApiProductControllerTest extends FrFunctionalTest
{
    private ProductRepository $productRepository;

    private ProductToArrayConvertor $productConvertor;


    public function setUp(): void
    {
        parent::setUp();

        $this->productRepository = static::getContainer()->get(ProductRepository::class);
        $this->productConvertor = static::getContainer()->get(ProductToArrayConvertor::class);
        $this->loadFixtures([ProductTestFixtures::class]);
    }
    //SEARCH
    public function testSearchWithEmptyQStringReturnZeroProducts()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_search'), [
            'q' => ''
        ]);
        $this->assertCount(
            0,
            json_decode($this->client->getResponse()->getContent())->products
        );
        $this->assertEquals(
            0,
            json_decode($this->client->getResponse()->getContent())->count
        );
    }
    public function testSearchReturnCorrectProducts()
    {
        $products = $this->productRepository->qSearch('obj', 4);
        $expectedResult = json_encode($this->productConvertor->convert($products));

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_search'), [
            'q' => 'obj',
            'limit' => 4
        ]);
        $result = $this->client->getResponse()->getContent();
        $this->assertEquals(json_decode($expectedResult), json_decode($result)->products);
    }
    public function testSearchApplyCorrectLimit()
    {
        $products = $this->productRepository->qSearch('obj', 4);
        $this->assertCount(2, $products, 'problème de fixtures : il devrait y avoir 2 produits correspondant au q "obj". Le test de limit est donc faussé');
        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_search'), [
            'q' => 'obj',
            'limit' => 1
        ]);
        $this->assertCount(
            1, 
            json_decode($this->client->getResponse()->getContent())->products
        );
    }
    public function testSearchReturnCorrectCount()
    {
        $count = $this->productRepository->countQSearch('obj');
        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_search'), [
            'q' => 'obj',
        ]);
        $this->assertEquals(
            $count, 
            json_decode($this->client->getResponse()->getContent())->count
        );
    }
    public function testSearchDatabaseQueriesCount()
    {
        $this->client->enableProfiler();

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_search'), [
            'q' => 'obj'
        ]);

        /** @var DoctrineDataCollector */
        $dbCollector = $this->client->getProfile()->getCollector('db');

        $this->assertEquals(3, $dbCollector->getQueryCount(), 'le pb peut venir de ProductRepository');
    }
    //INDEX
    public function testIndexCorrectCountProducts()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_index'), [
            'q' => 'obj'
        ]);
        $result = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals($result->count, count($result->products));
    }
    public function testIndexFilters()
    {
        $products = $this->productRepository->filter(
            (new SearchParams)
            ->setQ('obj')
        );
        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_index'), [
            'q' => 'obj',
        ]);
        $returnProducts = json_decode($this->client->getResponse()->getContent())->products;
        $this->assertEquals(count($products), count($returnProducts));
        $this->assertEquals($products[0]->getId(), $returnProducts[0]->id);
    }
    public function testIndexFilterCategory()
    {
        $this->loadFixtures([ProductWithOrWithoutCategoryTestFixtures::class]);
        $productsTest = $this->productRepository->findAll();
        $category = $productsTest[0]->getCategory();
        $category1 = $productsTest[1]->getCategory();

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_index'), [
            'categoryId' => $category->getId()
        ]);
        $returnProducts = json_decode($this->client->getResponse()->getContent())->products;
        $this->assertCount(count($category->getProducts()), $returnProducts);

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_index'), [
            'categoryId' => $category1->getId()
        ]);
        $returnProducts = json_decode($this->client->getResponse()->getContent())->products;
        $this->assertCount(count($category1->getProducts()), $returnProducts);
    }
    public function testIndexFilterSubCategory()
    {
        $productsTest = $this->productRepository->findAll();
        $subCategory = $productsTest[0]->getSubCategory();
        $subCategory1 = $productsTest[3]->getSubCategory();

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_index'), [
            'subCategoryId' => $subCategory->getId()
        ]);
        $returnProducts = json_decode($this->client->getResponse()->getContent())->products;
        $this->assertCount(count($subCategory->getProducts()), $returnProducts);

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_index'), [
            'subCategoryId' => $subCategory1->getId()
        ]);
        $returnProducts = json_decode($this->client->getResponse()->getContent())->products;
        $this->assertCount(count($subCategory1->getProducts()), $returnProducts);
    }
    public function testIndexDatabaseQueriesCount()
    {
        $this->client->enableProfiler();
        
        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_index'));

        /** @var DoctrineDataCollector */
        $dbCollector = $this->client->getProfile()->getCollector('db');
        $this->assertLessThan(5, $dbCollector->getQueryCount());
    }
    
    
    //getSuggestedProducts
    public function testGetSuggestedProductsWithNoIdParam()
    {
        $this->expectException(MissingMandatoryParametersException::class);
        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_getSuggestedProducts'));
    }
    public function testGetSuggestedProductsWithInvalidIdParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_getSuggestedProducts', [
            'id' => 9875642132456
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testGetSuggestedProductsCorrectCount()
    {
        $products = $this->productRepository->findAll();
        $product = $products[0];
        $product->addSuggestedProduct($products[1]);
        $product->addSuggestedProduct($products[2]);
        //on persiste ces ajouts en db
        $this->client->getContainer()->get(EntityManagerInterface::class)->flush();

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_getSuggestedProducts', [
            'id' => $product->getId()
        ]));
        $this->assertEquals(
            $product->getSuggestedProducts()->count(),
            count(json_decode($this->client->getResponse()->getContent()))
        );
        $this->assertLessThanOrEqual($product->getSuggestedProducts()->count(), 2);
    }
    public function testGetSuggestedProductsContainCorrectKeys()
    {
        $product = $this->findEntity(ProductRepository::class);
        $this->assertNotEquals(0, $product->getSuggestedProducts()->count(), 'le test est faussé, le product n\'a aucun suggestedProduct');

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_getSuggestedProducts', [
            'id' => $product->getId()
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals([
            'id', 'designation', 'fullName', 'categoryName', 'subCategoryName', 'price', 'formatedPrice', 'target', 'firstPicture', 'stock', 'material'
        ], array_keys(get_object_vars($data[0])));
    }
    public function testGetSuggestedProductsContainCorrectValues()
    {
        $product = $this->findEntity(ProductRepository::class);
        $this->assertNotEquals(0, $product->getSuggestedProducts()->count(), 'le test est faussé, le product n\'a aucun suggestedProduct');

        $this->client->request('GET', $this->urlGenerator->generate('fr_api_product_getSuggestedProducts', [
            'id' => $product->getId()
        ]));
        $data = json_decode($this->client->getResponse()->getContent());
        $this->assertEquals(
            $product->getSuggestedProducts()->get(0)->getDesignation(),
            $data[0]->designation
        );
        $this->assertEquals(
            $product->getSuggestedProducts()->get(0)->getPrice(),
            $data[0]->price
        );
    }
}