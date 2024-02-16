<?php
namespace App\Tests\Functional\Controller\En\Shop\Category;

use App\Entity\SubCategory;
use App\Tests\Functional\FunctionalTest;
use App\Repository\SubCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\Tests\CategoryTestFixtures;


/**
 * @group FunctionalShop
 */
class SubCategoryControllerTest extends FunctionalTest
{
    private SubCategory $subCategory;

    public function setUp(): void 
    {
        parent::setUp();

        $this->loadFixtures([CategoryTestFixtures::class]);
        $this->subCategory = $this->findEntity(SubCategoryRepository::class);
    }

    public function testShowPageRender()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_subCategory_show', [
            'categorySlug' => $this->subCategory->getParentCategory()->getEnSlug(),
            'subCategorySlug' => $this->subCategory->getEnSlug()
        ]));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->subCategory->getEnName());
    }
    public function testShowWithInexistantCategorySlugParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_subCategory_show', [
            'categorySlug' => 'slug-inexistant',
            'subCategorySlug' => $this->subCategory->getEnSlug()
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowWithInexistantSubCategorySlugParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_subCategory_show', [
            'categorySlug' => $this->subCategory->getParentCategory()->getEnSlug(),
            'subCategorySlug' => 'slug-inexistant'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowWithInexistantFrSubCategorySlugParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_subCategory_show', [
            'categorySlug' => $this->subCategory->getParentCategory()->getEnSlug(),
            'subCategorySlug' => $this->subCategory->getSlug()
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowContainsCorrectBreadCrumb()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_subCategory_show', [
            'categorySlug' => $this->subCategory->getParentCategory()->getEnSlug(),
            'subCategorySlug' => $this->subCategory->getEnSlug()
        ]));
        $this->assertSelectorTextContains('.breadcrumb-home-link', 'Home');
        $this->assertEquals(
            $this->urlGenerator->generate('en_home'),
            $crawler->filter('.breadcrumb-home-link')->attr('href')
        );
        $this->assertSelectorTextContains('.breadcrumb-link', $this->subCategory->getParentCategory()->getEnName());
        $this->assertEquals(
            $this->urlGenerator->generate('en_category_show', ['slug' => $this->subCategory->getParentCategory()->getEnSlug()]),
            $crawler->filter('.breadcrumb-link')->attr('href')
        );
        $this->assertSelectorTextContains('.breadcrumb-item', $this->subCategory->getEnName());
    }
}