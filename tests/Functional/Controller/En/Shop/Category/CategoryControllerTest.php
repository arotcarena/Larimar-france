<?php
namespace App\Tests\Functional\Controller\En\Shop\Category;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Tests\Functional\FunctionalTest;
use Symfony\Component\HttpFoundation\Response;
use App\DataFixtures\Tests\CategoryTestFixtures;



/**
 * @group FunctionalShop
 */
class CategoryControllerTest extends FunctionalTest
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
        $this->client->request('GET', $this->urlGenerator->generate('en_category_show', [
            'slug' => $this->category->getEnSlug()
        ]));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', $this->category->getEnName());
    }
    public function testShowWithInexistantCategorySlugParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_category_show', [
            'slug' => 'slug-inexistant'
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowWithFrCategorySlugParam()
    {
        $this->client->request('GET', $this->urlGenerator->generate('en_category_show', [
            'slug' => $this->category->getSlug()
        ]));
        $this->assertResponseStatusCodeSame(Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function testShowContainsCorrectBreadCrumb()
    {
        $crawler = $this->client->request('GET', $this->urlGenerator->generate('en_category_show', [
            'slug' => $this->category->getEnSlug()
        ]));
        $this->assertSelectorTextContains('.breadcrumb-item', $this->category->getEnName());
        $this->assertSelectorTextContains('.breadcrumb-home-link', 'Home');
        $this->assertEquals(
            $this->urlGenerator->generate('en_home'),
            $crawler->filter('.breadcrumb-home-link')->attr('href')
        );
    }
    
}