<?php
namespace App\Service;

use App\Entity\Product;
use App\Entity\SubCategory;
use App\Form\Error\SlugError;
use App\Repository\ProductRepository;
use App\Repository\SubCategoryRepository;

class UniqueSlugVerificator
{
    public function __construct(
        private ProductRepository $productRepository,
        private SubCategoryRepository $subCategoryRepository
    )
    {
        
    }

    /**
     * @param Product|SubCategory $subject
     *
     * @return null|SlugError
     */
    public function verify($subject): ?SlugError
    {
        if($subject instanceof Product)
        {
            return $this->verifyProduct($subject);
        }
        if($subject instanceof SubCategory)
        {
            return $this->verifySubCategory($subject);
        }
    }

    private function verifyProduct(Product $product): ?SlugError 
    {
        $productWithSameSlug = $this->productRepository->findOneBy([
            'category' => $product->getCategory(),
            'subCategory' => $product->getSubCategory(),
            'slug' => $product->getSlug()
        ]);
        if($productWithSameSlug && $productWithSameSlug->getId() !== $product->getId())
        {
            return new SlugError('slug', 'Il existe déjà un produit avec ce slug dans la même catégorie/sous-catégorie');
        }

        $productWithSameEnSlug = $this->productRepository->findOneBy([
            'category' => $product->getCategory(),
            'subCategory' => $product->getSubCategory(),
            'enSlug' => $product->getEnSlug()
        ]);
        if($productWithSameEnSlug && $productWithSameEnSlug->getId() !== $product->getId())
        {
            return new SlugError('enSlug', 'Il existe déjà un produit avec ce slug EN dans la même catégorie/sous-catégorie');
        }

        return null;
    }

    private function verifySubCategory(SubCategory $subCategory): ?SlugError 
    {
        $subCategoryWithSameSlug = $this->subCategoryRepository->findOneBy([
            'parentCategory' => $subCategory->getParentCategory(),
            'slug' => $subCategory->getSlug()
        ]);
        if($subCategoryWithSameSlug && $subCategoryWithSameSlug->getId() !== $subCategory->getId())
        {
            return new SlugError('slug', 'Il existe déjà une sous-catégorie avec ce slug dans la même catégorie parente');
        }

        $subCategoryWithSameEnSlug = $this->subCategoryRepository->findOneBy([
            'parentCategory' => $subCategory->getParentCategory(),
            'enSlug' => $subCategory->getEnSlug()
        ]);
        if($subCategoryWithSameEnSlug && $subCategoryWithSameEnSlug->getId() !== $subCategory->getId())
        {
            return new SlugError('enSlug', 'Il existe déjà une sous-catégorie avec ce slug EN dans la même catégorie parente');
        }

        return null;
    }
}


