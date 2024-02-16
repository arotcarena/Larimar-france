<?php
namespace App\Convertor\En;

use App\Entity\Product;
use App\Convertor\ConvertorTrait;
use App\Convertor\ShopConvertorTrait;

class ProductToArrayConvertor
{
    use ConvertorTrait;
    use ShopConvertorTrait;

    /**
     * @param Product|Product[] $data
     * @return array
     */
    public function convert($data): array
    {
        return $this->convertOneOrMore($data);
    }
    
    public function convertOne(Product $product): array 
    {
        $fullName = $product->getEnDesignation() .   
                    ($product->getCategory() ? ' in ' . $product->getCategory()->getEnName(): '') .
                    ($product->getSubCategory() ? ' > ' . $product->getSubCategory()->getEnName(): '');

        return [
            'id' => $product->getId(),
            'designation' => $product->getEnDesignation(),
            'fullName' => $fullName,
            'categoryName' => $product->getCategory() ? $product->getCategory()->getEnName(): null,
            'subCategoryName' => $product->getSubCategory() ? $product->getSubCategory()->getEnName(): null,
            'price' => $product->getPrice(),
            'formatedPrice' => $this->priceFormater->format($product->getPrice()),
            'target' => $this->productShowUrlResolver->getUrl($product, 'en'),
            'firstPicture' => [
                'path' => $this->picturePathResolver->getPath($product->getFirstPicture(), 'small_index'),
                'alt' => $this->picturePathResolver->getAlt($product->getFirstPicture())
            ],
            'stock' => $product->getStock(),
            'material' => $product->getMaterial()
        ];
    }
}