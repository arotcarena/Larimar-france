<?php
namespace App\Tests\Functional\Controller\Fr\Shop\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\Tests\CategoryTestFixtures;
use App\Tests\Functional\FrFunctionalTest;

/**
 * @group FunctionalShop
 */
class CategoryControllerTest extends FrFunctionalTest
{
    private Category $category;

    public function setUp(): void 
    {
        parent::setUp();

        $this->loadFixtures([CategoryTestFixtures::class]);
        $this->category = $this->findEntity(CategoryRepository::class);
    }

    public function testShowPageRender()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_category_show', [
            'slug' => $this->category->getSlug()
        ]));
        $this->assertResponseIsSuccessful();
    }
    public function testShowWithInexistantCategorySlugParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('fr_category_show', [
            'slug' => 'slug-inexistant'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowContainsCorrectBreadCrumb()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('fr_category_show', [
            'slug' => $this->category->getSlug()
        ]));
        $this->assertSelectorTextContains('.breadcrumb-item', $this->category->getName());
        $this->assertSelectorTextContains('.breadcrumb-home-link', 'Accueil');
        $this->assertEquals(
            $this->urlGenerator->generate('home'),
            $crawler->filter('.breadcrumb-home-link')->attr('href')
        );
    }
    
}