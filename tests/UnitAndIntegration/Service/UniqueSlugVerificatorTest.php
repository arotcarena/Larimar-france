<?php
namespace App\Tests\UnitAndIntegration\Service;

use App\Entity\Product;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Tests\Utils\FixturesTrait;
use App\Repository\ProductRepository;
use App\DataFixtures\Tests\ProductWithOrWithoutCategoryTestFixtures;
use App\Repository\SubCategoryRepository;
use App\Service\UniqueSlugVerificator;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @group Service
 */
class UniqueSlugVerificatorTest extends KernelTestCase
{
    use FixturesTrait;

    private UniqueSlugVerificator $verificator;

    public function setUp(): void 
    {
        parent::setUp();

        self::bootKernel();

        $this->loadFixtures([ProductWithOrWithoutCategoryTestFixtures::class]);
        $productRepository = static::getContainer()->get(ProductRepository::class);
        $subCategoryRepository = static::getContainer()->get(SubCategoryRepository::class);
        $this->verificator = new UniqueSlugVerificator($productRepository, $subCategoryRepository);
    }

    public function testExistingProductSlugWithSameCategoryAndSubCategory()
    {
        /** @var Product */
        $existingProduct = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);

        $product = (new Product)
                    ->setCategory($existingProduct->getCategory())
                    ->setSubCategory($existingProduct->getSubCategory())
                    ->setSlug($existingProduct->getSlug())
                    ;

        $this->assertNotNull($this->verificator->verify($product));
    }
    public function testExistingProductEnSlugWithSameCategoryAndSubCategory()
    {
        /** @var Product */
        $existingProduct = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);

        $product = (new Product)
                    ->setCategory($existingProduct->getCategory())
                    ->setSubCategory($existingProduct->getSubCategory())
                    ->setSlug('slug-tout-a-fait-valide')
                    ->setEnSlug($existingProduct->getEnSlug())
                    ;

        $this->assertNotNull($this->verificator->verify($product));
    }
    public function testExistingProductSlugWithDifferentCategoryOrSubCategory()
    {
        /** @var Product */
        $existingProduct = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);

        $product = (new Product)
                    ->setCategory($existingProduct->getCategory())
                    ->setSubCategory(new SubCategory)   // different SubCategory
                    ->setSlug($existingProduct->getSlug())
                    ;

        $this->assertNull($this->verificator->verify($product));
    }
    public function testExistingProductEnSlugWithDifferentCategoryOrSubCategory()
    {
        /** @var Product */
        $existingProduct = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);

        $product = (new Product)
                    ->setCategory($existingProduct->getCategory())
                    ->setSubCategory(new SubCategory)   // different SubCategory
                    ->setSlug('tout-a-fait-un-slug-valide')
                    ->setEnSlug($existingProduct->getEnSlug())
                    ;

        $this->assertNull($this->verificator->verify($product));
    }
    public function testUpdateProductKeepingSameSlug()
    {
        /** @var Product */
        $existingProduct = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $this->assertNull($this->verificator->verify($existingProduct));
    }
    public function testUpdateSubCategoryKeepingSameSlug()
    {
        /** @var Product */
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $existingSubCategory = $product->getSubCategory();
        $this->assertNull($this->verificator->verify($existingSubCategory));
    }
    public function testExistingSubCategorySlugWithSameParentCategory()
    {
        /** @var Product */
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $existingSubCategory = $product->getSubCategory();

        $subCategory = (new SubCategory)
                    ->setParentCategory($existingSubCategory->getParentCategory())
                    ->setSlug($existingSubCategory->getSlug())
                    ;

        $this->assertNotNull($this->verificator->verify($subCategory));
    }
    public function testExistingSubCategoryEnSlugWithSameParentCategory()
    {
        /** @var Product */
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $existingSubCategory = $product->getSubCategory();

        $subCategory = (new SubCategory)
                    ->setParentCategory($existingSubCategory->getParentCategory())
                    ->setSlug('un-slug-valide-tout-bon')
                    ->setEnSlug($existingSubCategory->getEnSlug())
                    ;

        $this->assertNotNull($this->verificator->verify($subCategory));
    }
    public function testExistingSubCategorySlugWithDifferentParentCategory()
    {
        /** @var Product */
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $existingSubCategory = $product->getSubCategory();

        $subCategory = (new SubCategory)
                    ->setParentCategory(new Category) // different Category
                    ->setSlug($existingSubCategory->getSlug())
                    ;
                    
        $this->assertNull($this->verificator->verify($subCategory));
    }
    public function testExistingSubCategoryEnSlugWithDifferentParentCategory()
    {
        /** @var Product */
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $existingSubCategory = $product->getSubCategory();

        $subCategory = (new SubCategory)
                    ->setParentCategory(new Category) // different Category
                    ->setSlug('un-bon-slug-valide')
                    ->setEnSlug($existingSubCategory->getEnSlug())
                    ;
                    
        $this->assertNull($this->verificator->verify($subCategory));
    }
    public function testOriginalProductSlug()
    {
        /** @var Product */
        $existingProduct = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);

        $product = (new Product)
                    ->setCategory($existingProduct->getCategory())
                    ->setSubCategory($existingProduct->getSubCategory()) 
                    ->setSlug('slug-original')
                    ;

        $this->assertNull($this->verificator->verify($product));
    }
    public function testOriginalSubCategorySlug()
    {
        /** @var Product */
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $existingSubCategory = $product->getSubCategory();

        $subCategory = (new SubCategory)
                    ->setParentCategory($existingSubCategory->getParentCategory())
                    ->setSlug('slug-original')
                    ;
                    
        $this->assertNull($this->verificator->verify($subCategory));
    }
    public function testErrorContainCorrectPropertyField()
    {
        /** @var Product */
        $product = $this->findEntity(ProductRepository::class, ['slug' => 'product-with-category-and-subcategory']);
        $existingSubCategory = $product->getSubCategory();

        $subCategory = (new SubCategory)
                    ->setParentCategory($existingSubCategory->getParentCategory())
                    ->setSlug('un-slug-valide-tout-bon')
                    ->setEnSlug($existingSubCategory->getEnSlug())
                    ;
        $slugError = $this->verificator->verify($subCategory);
        $this->assertIsString($slugError->getMessage());
        $this->assertEquals('enSlug', $slugError->getField());

        $subCategory = (new SubCategory)
        ->setParentCategory($existingSubCategory->getParentCategory())
        ->setSlug($existingSubCategory->getSlug())
        ->setEnSlug('un-slug-valide-tout-bon')
        ;
        $slugError = $this->verificator->verify($subCategory);
        $this->assertEquals('slug', $slugError->getField());
    }


    
}