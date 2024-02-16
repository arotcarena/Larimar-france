<?php
namespace App\Service;

use App\Repository\CategoryRepository;
use App\Convertor\En\CategoryToArrayConvertor as EnCategoryConvertor;
use App\Convertor\Fr\CategoryToArrayConvertor as FrCategoryConvertor;


class CategoryGlobalProvider
{
    public function __construct(
        private CategoryRepository $categoryRepository,
        private EnCategoryConvertor $en_categoryConvertor,
        private FrCategoryConvertor $fr_categoryConvertor

    )
    {

    }
    public function getJsonMenuList($lang = 'fr'): string
    {
        $categoryConvertor = $lang === 'en' ? $this->en_categoryConvertor: $this->fr_categoryConvertor;

        $categories = $this->categoryRepository->findAllOrderedForMenuList();

        $categoriesToArray = $categoryConvertor->convert($categories);

        return json_encode($categoriesToArray);
    }
}