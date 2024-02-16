<?php
namespace App\Convertor\En;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Convertor\ConvertorTrait;
use App\Convertor\ShopConvertorTrait;

class CategoryToArrayConvertor
{
    use ConvertorTrait;
    use ShopConvertorTrait;

    /**
     * @param Category[]|Category $data
     * @return array
     */
    public function convert($data): array 
    {
        return $this->convertOneOrMore($data);
    }

    private function convertOne(Category $category): array 
    {
        return [
            'id' => $category->getId(),
            'name' => str_replace(' ', '_', $category->getEnName()),
            'target' => $this->urlGenerator->generate('en_category_show', [
                'slug' => $category->getEnSlug()
            ]),
            'listPosition' => $category->getListPosition(),
            'subCategories' => $this->convertSubCategoriesToArray($category->getSubCategories())
        ];
    }  
    
    private function convertSubCategoryToArray(SubCategory $subCategory): array 
    {
        return [
            'id' => $subCategory->getId(),
            'name' => str_replace(' ', '_', $subCategory->getEnName()),
            'target' => $this->urlGenerator->generate('en_subCategory_show', [
                'categorySlug' => $subCategory->getParentCategory()->getEnSlug(),
                'subCategorySlug' => $subCategory->getEnSlug()
            ]),
            'picture' => [
                'path' => $this->picturePathResolver->getPath($subCategory->getPicture()),
                'alt' => str_replace(' ', '_', $subCategory->getPicture() !== null ? $subCategory->getPicture()->getAlt(): 'texte alternatif par défaut') 
            ],
            'listPosition' => $subCategory->getListPosition()
        ];
    }
    

    /**
     * @param SubCategory[] $subCategories
     * @return array
     */
    private function convertSubCategoriesToArray($subCategories): array 
    {
        $subCategoriesToArray = [];
        foreach($subCategories as $subCategory)
        {
            $subCategoriesToArray[] = $this->convertSubCategoryToArray($subCategory);
        }
        return $subCategoriesToArray;
    }
}