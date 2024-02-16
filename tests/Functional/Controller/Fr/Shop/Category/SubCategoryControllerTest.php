<?php
namespace App\Tests\Functional\Controller\Fr\Shop\Category;

use App\Entity\SubCategory;
use App\Repository\SubCategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\Tests\CategoryTestFixtures;
use App\Tests\Functional\FrFunctionalTest;

/**
 * @group FunctionalShop
 */
class SubCategoryControllerTest extends FrFunctionalTest
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
        $this->client->request('GET', $this->urlGenerator->generate('fr_subCategory_show', [
            'categorySlug' => $this->subCategory->getParentCategory()->getSlug(),
            'subCategorySlug' => $this->subCategory->getSlug()
        ]));
        $this->assertResponseIsSuccessful();
    }
    public function testShowWithInexistantCategorySlugParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_subCategory_show', [
            'categorySlug' => 'slug-inexistant',
            'subCategorySlug' => $this->subCategory->getSlug()
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowWithInexistantSubCategorySlugParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_subCategory_show', [
            'categorySlug' => $this->subCategory->getParentCategory()->getSlug(),
            'subCategorySlug' => 'slug-inexistant'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowContainsCorrectBreadCrumb()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('fr_subCategory_show', [
            'categorySlug' => $this->subCategory->getParentCategory()->getSlug(),
            'subCategorySlug' => $this->subCategory->getSlug()
        ]));
        $this->assertSelectorTextContains('.breadcrumb-home-link', 'Accueil');
        $this->assertEquals(
            $this->urlGenerator->generate('home'),
            $crawler->filter('.breadcrumb-home-link')->attr('href')
        );
        $this->assertSelectorTextContains('.breadcrumb-link', $this->subCategory->getParentCategory()->getName());
        $this->assertEquals(
            $this->urlGenerator->generate('fr_category_show', ['slug' => $this->subCategory->getParentCategory()->getSlug()]),
            $crawler->filter('.breadcrumb-link')->attr('href')
        );
        $this->assertSelectorTextContains('.breadcrumb-item', $this->subCategory->getName());
    }
}