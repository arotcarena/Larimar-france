<?php
namespace App\Tests\IntegrationOnly\Service;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\SubCategory;
use App\Service\ProductShowUrlResolver;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @group Service
 */
class ProductShowUrlResolverTest extends KernelTestCase
{
    private UrlGeneratorInterface $urlGenerator;

    private ProductShowUrlResolver $productShowUrlResolver;


    public function setUp(): void
    {
        parent::setUp();

        $this->urlGenerator = static::getContainer()->get(UrlGeneratorInterface::class);

        $this->productShowUrlResolver = static::getContainer()->get(ProductShowUrlResolver::class); 
    }

    public function testInvalidProductParam()
    {
        $product = new Product;
        $this->expectException(InvalidParameterException::class);
        $this->productShowUrlResolver->getUrl($product);

        $product = $this->createValidProduct()
                        ->setCategory(new Category)
                        ;
        $this->expectException(InvalidParameterException::class);
        $this->productShowUrlResolver->getUrl($product);
    }

    public function testProductWithCategoryAndSubCategory()
    {
        $product = $this->createValidProduct()
                        ->setCategory(
                            (new Category)
                            ->setSlug('slug-de-category')
                        )
                        ->setSubCategory(
                            (new SubCategory)
                            ->setSlug('slug-de-subcategory')
                        )
                        ;
        
        $this->assertEquals(
            $this->urlGenerator->generate('fr_product_show_withCategoryAndSubCategory', [   // fr car c'est par défaut lorsqu'il n'y a pas de Request->getPathInfo()
                'publicRef' => $product->getPublicRef(),
                'slug' => $product->getSlug(),
                'categorySlug' => $product->getCategory()->getSlug(),
                'subCategorySlug' => $product->getSubCategory()->getSlug()
            ]),
            $this->productShowUrlResolver->getUrl($product)
        );
    }
    public function testEnProductWithCategoryAndSubCategory()
    {
        $product = $this->createValidProduct()
                        ->setCategory(
                            (new Category)
                            ->setSlug('slug-de-category')
                            ->setEnSlug('slug-de-category-en')
                        )
                        ->setSubCategory(
                            (new SubCategory)
                            ->setSlug('slug-de-subcategory')
                            ->setEnSlug('slug-de-subcategory-en')
                        )
                        ;
        
        $this->assertEquals(
            $this->urlGenerator->generate('en_product_show_withCategoryAndSubCategory', [   // fr car c'est par défaut lorsqu'il n'y a pas de Request->getPathInfo()
                'publicRef' => $product->getPublicRef(),
                'slug' => $product->getEnSlug(),
                'categorySlug' => $product->getCategory()->getEnSlug(),
                'subCategorySlug' => $product->getSubCategory()->getEnSlug()
            ]),
            $this->productShowUrlResolver->getUrl($product, 'en')
        );
    }

    public function testProductWithCategory()
    {
        $product = $this->createValidProduct()
                        ->setCategory(
                            (new Category)
                            ->setSlug('slug-de-category')
                        )
                        ;
        
        $this->assertEquals(
            $this->urlGenerator->generate('fr_product_show_withCategory', [
                'publicRef' => $product->getPublicRef(),
                'slug' => $product->getSlug(),
                'categorySlug' => $product->getCategory()->getSlug()
            ]),
            $this->productShowUrlResolver->getUrl($product)
        );
    }

    public function testEnProductWithCategory()
    {
        $product = $this->createValidProduct()
                        ->setCategory(
                            (new Category)
                            ->setSlug('slug-de-category')
                            ->setEnSlug('slug-de-category-en')
                        )
                        ;
        
        $this->assertEquals(
            $this->urlGenerator->generate('en_product_show_withCategory', [
                'publicRef' => $product->getPublicRef(),
                'slug' => $product->getEnSlug(),
                'categorySlug' => $product->getCategory()->getEnSlug()
            ]),
            $this->productShowUrlResolver->getUrl($product, 'en')
        );
    }

    public function testProductWithoutCategory()
    {
        $product = $this->createValidProduct();
        
        $this->assertEquals(
            $this->urlGenerator->generate('fr_product_show', [
                'publicRef' => $product->getPublicRef(),
                'slug' => $product->getSlug()
            ]),
            $this->productShowUrlResolver->getUrl($product)
        );
    }

    public function testEnProductWithoutCategory()
    {
        $product = $this->createValidProduct();
        
        $this->assertEquals(
            $this->urlGenerator->generate('en_product_show', [
                'publicRef' => $product->getPublicRef(),
                'slug' => $product->getEnSlug()
            ]),
            $this->productShowUrlResolver->getUrl($product, 'en')
        );
    }


    private function createValidProduct(): Product
    {
        return (new Product)
                ->setPublicRef('123456af')
                ->setSlug('slug')
                ->setEnSlug('en-slug')
                ;
    }
}