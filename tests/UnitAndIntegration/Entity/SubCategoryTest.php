<?php
namespace App\Tests\UnitAndIntegration\Entity;

use DateTimeImmutable;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Tests\Utils\FixturesTrait;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use App\DataFixtures\Tests\CategoryTestFixtures;
use App\Tests\UnitAndIntegration\Entity\EntityTest;

/**
 * @group Entity
 */
class SubCategoryTest extends EntityTest
{
    use FixturesTrait;


    public function testValidSubCategory()
    {
        $this->assertHasErrors(0, $this->createValidSubCategory());
    }
    public function testInvalidBlankName()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setName('')
        );
    }
    public function testInvalidTooLongName()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setName($this->moreThan200Caracters)
        );
    }
    public function testInvalidBlankEnName()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setEnName('')
        );
    }
    public function testInvalidTooLongEnName()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setEnName($this->moreThan200Caracters)
        );
    }
    public function testInvalidBlankSlug()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setSlug('')
        );
    }
    public function testInvalidTooLongSlug()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setSlug(str_repeat('too-long-valid-sluggg', 10))  // > 200 car.
        );
    }
    public function testInvalidSlugFormat()
    {
        $this->assertHasErrors(
            1, 
            $this->createValidSubCategory()->setSlug('Unsluginvalide')
        );
        $this->assertHasErrors(
            1, 
            $this->createValidSubCategory()->setSlug('unslug invalide')
        );
        $this->assertHasErrors(
            1, 
            $this->createValidSubCategory()->setSlug('unslug-inval.de')
        );
    }
    public function testInvalidBlankEnSlug()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setEnSlug('')
        );
    }
    public function testInvalidTooLongEnSlug()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setEnSlug(str_repeat('too-long-valid-sluggg', 10))  // > 200 car.
        );
    }
    public function testInvalidEnSlugFormat()
    {
        $this->assertHasErrors(
            1, 
            $this->createValidSubCategory()->setEnSlug('Unsluginvalide')
        );
        $this->assertHasErrors(
            1, 
            $this->createValidSubCategory()->setEnSlug('unslug invalide')
        );
        $this->assertHasErrors(
            1, 
            $this->createValidSubCategory()->setEnSlug('unslug-inval.de')
        );
    }
    public function testInvalidNegativeOrZeroListPosition()
    {
        $this->assertHasErrors(
            1, 
            $this->createValidSubCategory()->setListPosition(-4)
        );
        $this->assertHasErrors(
            1, 
            $this->createValidSubCategory()->setListPosition(0)
        );
    }
    public function testInvalidNullParentCategory()
    {
        $this->assertHasErrors(
            1,
            $this->createValidSubCategory()->setParentCategory(null)
        );
    }

    private function createValidSubCategory(): SubCategory
    {
        return (new SubCategory)
                ->setName('Nom de catégorie valide')
                ->setEnName('SubCategory valid name')
                ->setSlug('slug-de-categorie-valide')
                ->setEnSlug('valid-subcategory-slug')
                ->setParentCategory(new Category)
                ->setCreatedAt(new DateTimeImmutable())
                ;
    }
}