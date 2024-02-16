<?php
namespace App\Service;

use App\Entity\Product;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductShowUrlResolver
{
    public function __construct(
        private UrlGeneratorInterface $urlGeneratorInterface,
    )
    {
        
    }

    public function getUrl(Product $product, string $lang = 'fr'): string
    {
        $routeName = $lang . '_product_show';

        $slug = $lang === 'en' ? $product->getEnSlug(): $product->getSlug();
        $params = [
            'publicRef' => $product->getPublicRef(),
            'slug' => $slug
        ];

        if($product->getCategory())
        {
            $routeName .= '_withCategory';
            $categorySlug = $lang === 'en' ? $product->getCategory()->getEnSlug(): $product->getCategory()->getSlug();
            $params['categorySlug'] = $categorySlug;
        }
        if($product->getCategory() && $product->getSubCategory())
        {
            $routeName .= 'AndSubCategory';
            $subCategorySlug = $lang === 'en' ? $product->getSubCategory()->getEnSlug(): $product->getSubCategory()->getSlug();
            $params['subCategorySlug'] = $subCategorySlug;
        }

        return $this->urlGeneratorInterface->generate($routeName, $params);
    }
}