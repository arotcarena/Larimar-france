<?php
namespace App\Tests\UnitAndIntegration\Service;

use stdClass;
use App\Entity\Category;
use PHPUnit\Framework\TestCase;
use App\Tests\Utils\FixturesTrait;
use App\Repository\CategoryRepository;
use App\Service\CategoryGlobalProvider;
use PHPUnit\Framework\MockObject\MockObject;
use App\Convertor\Fr\CategoryToArrayConvertor as FrCategoryConvertor;
use App\Convertor\En\CategoryToArrayConvertor as EnCategoryConvertor;


/**
 * @group Service
 */
class CategoryGlobalProviderTest extends TestCase
{
    use FixturesTrait;

    private MockObject|CategoryRepository $categoryRepository;

    private MockObject|FrCategoryConvertor $fr_categoryConvertor;

    private MockObject|EnCategoryConvertor $en_categoryConvertor;

    private CategoryGlobalProvider $categoryGlobalProvider;


    public function setUp(): void 
    {
        $this->categoryRepository = $this->createMock(CategoryRepository::class);
        $this->fr_categoryConvertor = $this->createMock(FrCategoryConvertor::class);
        $this->en_categoryConvertor = $this->createMock(EnCategoryConvertor::class);


        $this->categoryGlobalProvider = new CategoryGlobalProvider($this->categoryRepository, $this->en_categoryConvertor, $this->fr_categoryConvertor);
    }

    public function testReturnFormatIsJson()
    {
        
        $this->assertJson(
            $this->categoryGlobalProvider->getJsonMenuList()
        );
    }

    //FR

    public function testUseCorrectlyCategoryRepository()
    {
        $this->categoryRepository->expects($this->once())
                            ->method('findAllOrderedForMenuList')
                            ;
        
        $this->categoryGlobalProvider->getJsonMenuList();
    }

    public function testUseCorrectCategoryConvertor()
    {
        $this->en_categoryConvertor->expects($this->never())
                                    ->method('convert')
                                    ;
        $this->fr_categoryConvertor->expects($this->once())
                            ->method('convert')
                            ;
        
        $this->categoryGlobalProvider->getJsonMenuList();
    }

    public function testCorrectSequenceOfFunctions()
    {
       $category = new Category;
       $this->categoryRepository->expects($this->once())
                            ->method('findAllOrderedForMenuList')
                            ->willReturn($category)
                            ;
        $this->fr_categoryConvertor->expects($this->once())
                                    ->method('convert')
                                    ->with($category)
                                    ->willReturn(['test'])
                                    ;
        $this->assertEquals(json_encode(['test']), $this->categoryGlobalProvider->getJsonMenuList());
    }

    public function testCorrectReturnValue()
    {
         $this->fr_categoryConvertor->expects($this->once())
                                     ->method('convert')
                                     ->willReturn([
                                        ['id' => 1, 'name' => 'catégorie1'],
                                        ['id' => 2, 'name' => 'catégorie2']
                                     ])
                                     ;
        $data = $this->categoryGlobalProvider->getJsonMenuList();
        $this->assertInstanceOf(
            stdClass::class,
            json_decode($data)[0]
        );
        $this->assertEquals('catégorie1', json_decode($data)[0]->name);
    }

    // EN

    public function testEnUseCorrectlyCategoryRepository()
    {
        $this->categoryRepository->expects($this->once())
                            ->method('findAllOrderedForMenuList')
                            ;
        
        $this->categoryGlobalProvider->getJsonMenuList('en');
    }

    public function testEnUseCorrectCategoryConvertor()
    {
        $this->fr_categoryConvertor->expects($this->never())
                                ->method('convert')
                                ;

        $this->en_categoryConvertor->expects($this->once())
                            ->method('convert')
                            ;
        
        $this->categoryGlobalProvider->getJsonMenuList('en');
    }

    public function testEnCorrectSequenceOfFunctions()
    {
       $category = new Category;
       $this->categoryRepository->expects($this->once())
                            ->method('findAllOrderedForMenuList')
                            ->willReturn($category)
                            ;
        $this->en_categoryConvertor->expects($this->once())
                                    ->method('convert')
                                    ->with($category)
                                    ->willReturn(['test'])
                                    ;
        $this->assertEquals(json_encode(['test']), $this->categoryGlobalProvider->getJsonMenuList('en'));
    }

    public function testEnCorrectReturnValue()
    {
         $this->en_categoryConvertor->expects($this->once())
                                     ->method('convert')
                                     ->willReturn([
                                        ['id' => 1, 'name' => 'category1'],
                                        ['id' => 2, 'name' => 'category2']
                                     ])
                                     ;
        $data = $this->categoryGlobalProvider->getJsonMenuList('en');
        $this->assertInstanceOf(
            stdClass::class,
            json_decode($data)[0]
        );
        $this->assertEquals('category1', json_decode($data)[0]->name);
    }

}

