<?php
namespace App\Convertor\Fr;

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
        $fullName = $product->getDesignation() .   
                    ($product->getCategory() ? ' dans ' . $product->getCategory()->getName(): '') .
                    ($product->getSubCategory() ? ' > ' . $product->getSubCategory()->getName(): '');

        return [
            'id' => $product->getId(),
            'designation' => $product->getDesignation(),
            'fullName' => $fullName,
            'categoryName' => $product->getCategory() ? $product->getCategory()->getName(): null,
            'subCategoryName' => $product->getSubCategory() ? $product->getSubCategory()->getName(): null,
            'price' => $product->getPrice(),
            'formatedPrice' => $this->priceFormater->format($product->getPrice()),
            'target' => $this->productShowUrlResolver->getUrl($product, 'fr'),
            'firstPicture' => [
                'path' => $this->picturePathResolver->getPath($product->getFirstPicture(), 'small_index'),
                'alt' => $this->picturePathResolver->getAlt($product->getFirstPicture())
            ],
            'stock' => $product->getStock(),
            'material' => $product->getMaterial()
        ];
    }
}