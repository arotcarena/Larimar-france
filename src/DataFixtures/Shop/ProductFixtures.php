<?php
namespace App\DataFixtures\Shop;

use App\Config\TextConfig;
use Faker\Factory;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use Bezhanov\Faker\Provider\Commerce;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Shop\CategoryFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class ProductFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private Generator $faker;

    private Generator $en_faker;

    private $colorsTranslate = [
        'blanc' => 'white',
        'noir' => 'black',
        'jaune' => 'yellow',
        'vert' => 'green',
        'rouge' => 'red',
        'bleu' => 'blue'
    ];

    public function __construct(
        private SluggerInterface $slugger,
        private CategoryRepository $categoryRepository
    )
    {
        $this->faker = Factory::create('fr_FR');
        $this->faker->addProvider(new Commerce($this->faker));

        $this->en_faker = Factory::create('en_EN');
        $this->en_faker->addProvider(new Commerce($this->faker));
    }
    
    public function load(ObjectManager $manager)
    {
        $categories = $this->categoryRepository->findAll();

        $products = []; // pour suggestedProducts
        for ($i=0; $i < 100; $i++) { 
            $designation = $this->faker->productName();
            $enDesignation = $this->en_faker->productName();
            /** @var Category */
            $category = $this->faker->randomElement($categories);
            $subCategory = $this->faker->randomElement($category->getSubCategories()->toArray());
            $product = new Product;
            $product->setPublicRef(str_shuffle('aAzZeErRtT0123456789'))
                    ->setPrivateRef(str_shuffle('aAzZeErRtT0123456789'))
                    ->setDesignation($designation)
                    ->setEnDesignation($enDesignation)
                    ->setSlug(
                        strtolower($this->slugger->slug($designation))
                    )
                    ->setEnSlug(
                        strtolower($this->slugger->slug($enDesignation))
                    )
                    ->setDescription($this->faker->text())
                    ->setEnDescription($this->en_faker->text())
                    ->setPrice(random_int(2000, 20000))
                    ->setStock(random_int(0, 10))
                    ->setCategory($category)
                    ->setSubCategory($subCategory)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setMaterial($this->faker->randomElement(array_keys(TextConfig::PRODUCT_MATERIALS)))
                    ->setTotalDimension(random_int(10, 100))
                    ->setCabochonDimension(random_int(5, 20))
                    ->setWeight(random_int(10, 100))
                    ->setFingerSize(random_int(10, 20))
                    ->setColor($this->faker->randomElement(array_keys($this->colorsTranslate)))
                    ->setEnColor($this->colorsTranslate[$product->getColor()])
                    ;
            $manager->persist($product);
            $products[] = $product;
        }

        foreach($products as $product)
        {
            $suggestedProduct = $this->faker->randomElement($products);
            if($product->getSlug() !== $suggestedProduct->getSlug())
            {
                $product->addSuggestedProduct($suggestedProduct);
            }
        }

        $product = new Product;
        $product->setPublicRef(str_shuffle('aAzZeErRtT0123456789'))
        ->setPrivateRef(str_shuffle('aAzZeErRtT0123456789'))
        ->setDesignation('produit sans sous-catégorie')
        ->setEnDesignation('productwithoutsubcategory')
        ->setSlug('produit-sans-ss-categorie')
        ->setEnSlug('product-without-subcategory')
        ->setPrice(5000)
        ->setStock(1)
        ->setCategory($this->faker->randomElement($categories))
        ->setCreatedAt(new DateTimeImmutable())
        ->addSuggestedProduct($this->faker->randomElement($products))
        ;
        $manager->persist($product);

        $product = new Product;
        $product->setPublicRef(str_shuffle('aAzZeErRtT0123456789'))
        ->setPrivateRef(str_shuffle('aAzZeErRtT0123456789'))
        ->setDesignation('produitsanscatégorie')
        ->setEnDesignation('productwithoutcategory')
        ->setSlug('produit-sans-categorie')
        ->setEnSlug('product-without-category')
        ->setPrice(random_int(2000, 20000))
        ->setStock(1)
        ->setCreatedAt(new DateTimeImmutable())
        ->addSuggestedProduct($this->faker->randomElement($products))
        ;
        $manager->persist($product);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}