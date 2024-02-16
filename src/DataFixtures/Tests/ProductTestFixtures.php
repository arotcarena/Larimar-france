<?php
namespace App\DataFixtures\Tests;

use Faker\Factory;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Category;
use Bezhanov\Faker\Provider\Commerce;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Tests\CategoryTestFixtures;
use App\Entity\Picture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ProductTestFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;
    private Generator $en_faker;


    public function __construct(
        private SluggerInterface $slugger,
        private CategoryRepository $categoryRepository
    )
    {
        $this->faker = Factory::create();
        $this->faker->addProvider(new Commerce($this->faker));

        $this->en_faker = Factory::create('en_EN');
        $this->en_faker->addProvider(new Commerce($this->faker));
    }
    
    public function load(ObjectManager $manager)
    {
        $categories = $this->categoryRepository->findAll();

        $products = []; // pour suggestedProducts
        for ($i=0; $i < 5; $i++) { 
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
                    ->setStock(100)
                    ->setCategory($category)
                    ->setSubCategory($subCategory)
                    ->setCreatedAt(new DateTimeImmutable($this->faker->dateTime()->format('Y:m:d H:i:s')))
                    ->addPicture(
                        (new Picture)
                        ->setListPosition(2)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setFileName('fichier')
                        ->setFileSize(5)
                        ->setAlt('texte alternatif')
                    )
                    ->addPicture(
                        (new Picture)
                        ->setListPosition(1)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setFileName('fichier')
                        ->setFileSize(5)
                        ->setAlt('texte alternatif')
                    )
                    ->addPicture(
                        (new Picture)
                        ->setListPosition(3)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setFileName('fichier')
                        ->setFileSize(5)
                        ->setAlt('texte alternatif')
                    )
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

        $category = $this->faker->randomElement($categories);
        $subCategory = $this->faker->randomElement($category->getSubCategories()->toArray());
        $product = new Product;
        $product->setPublicRef(str_shuffle('aAzZeErRtT0123456789'))
                ->setPrivateRef(str_shuffle('aAzZeErRtT0123456789'))
                ->setDesignation('mon objet')
                ->setEnDesignation('my object')
                ->setSlug(
                    'mon-objet'
                )
                ->setEnSlug(
                    'my-object'
                )
                ->setDescription('La description de mon objet')
                ->setEnDescription('My Object full description')
                ->setPrice(35000)
                ->setStock(50)
                ->setCategory($category)
                ->setSubCategory($subCategory)
                ->setCreatedAt(new DateTimeImmutable())
                ->addPicture(
                    (new Picture)
                    ->setListPosition(2)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif')
                )
                ->addPicture(
                    (new Picture)
                    ->setListPosition(1)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif')
                )
                ->addSuggestedProduct($this->faker->randomElement($products))
                ;
        $manager->persist($product);
        $monObjet = $product; // pour product with suggestedProducts


        $category = $this->faker->randomElement($categories);
        $subCategory = $this->faker->randomElement($category->getSubCategories()->toArray());
        $product = new Product;
        $product->setPublicRef(str_shuffle('aAzZeErRtT0123456789'))
                ->setPrivateRef(str_shuffle('aAzZeErRtT0123456789'))
                ->setDesignation('objet')
                ->setEnDesignation('object')
                ->setSlug(
                    'objet'
                )
                ->setEnSlug(
                    'object'
                )
                ->setDescription('la description de objet')
                ->setEnDescription('object full description')
                ->setPrice(1000)
                ->setStock(50)
                ->setCategory($category)
                ->setSubCategory($subCategory)
                ->setCreatedAt(new DateTimeImmutable())
                ->addPicture(
                    (new Picture)
                    ->setListPosition(3)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif')
                )
                ->addPicture(
                    (new Picture)
                    ->setListPosition(1)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif pour test')
                )
                ->addPicture(
                    (new Picture)
                    ->setListPosition(2)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif')
                )
                ->addSuggestedProduct($this->faker->randomElement($products))
                ;
        $manager->persist($product);
        $objet = $product; // pour product with suggestedProducts



        $category = $this->faker->randomElement($categories);
        $subCategory = $this->faker->randomElement($category->getSubCategories()->toArray());
        $product = new Product;
        $product->setPublicRef('publicRef')
                ->setPrivateRef(str_shuffle('aAzZeErRtT0123456789'))
                ->setDesignation('public ref test')
                ->setEnDesignation('public ref test en designation')
                ->setSlug(
                    'public-ref-test'
                )
                ->setEnSlug('public-ref-test-en')
                ->setDescription('description du produit public ref test')
                ->setEnDescription('public ref test product description')
                ->setPrice(2000)
                ->setStock(50)
                ->setCategory($category)
                ->setSubCategory($subCategory)
                ->setCreatedAt(new DateTimeImmutable())
                ->addPicture(
                    (new Picture)
                    ->setListPosition(2)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif')
                )
                ->addPicture(
                    (new Picture)
                    ->setListPosition(1)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif')
                )
                ->addPicture(
                    (new Picture)
                    ->setListPosition(3)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif')
                )
                ->addSuggestedProduct($this->faker->randomElement($products))
                ;
        $manager->persist($product);


        /*product with suggestedProducts*/
        $product = new Product;
        $product->setPublicRef('fdsuioezjklfndsklhfjdk')
                ->setPrivateRef('fjdksqhfjkdhuzeihfjkdhu')
                ->setDesignation('product with suggestedProducts')
                ->setEnDesignation('en designation for product with suggestedProducts')
                ->setSlug(
                    'product-with-suggested-products'
                )
                ->setEnSlug('product-with-suggested-products-en-slug')
                ->setPrice(2000)
                ->setStock(50)
                ->setCreatedAt(new DateTimeImmutable())
                ->addSuggestedProduct($objet)
                ->addSuggestedProduct($monObjet)
                ;
        $manager->persist($product);


        /*product with no stock*/
        $product = new Product;
        $product->setPublicRef('fdhsjkfdsjqfdjkqheuiyruez')
                ->setPrivateRef('fdsjkfzeerueozurio')
                ->setDesignation('product with no stock')
                ->setEnDesignation('en designation for product with no stock')
                ->setSlug(
                    'product-with-no-stock'
                )
                ->setEnSlug('product-with-no-stock-en-slug')
                ->setPrice(2000)
                ->setStock(0)
                ->setCreatedAt(new DateTimeImmutable())
                ->addSuggestedProduct($this->faker->randomElement($products))
                ;
        $manager->persist($product);


        /* product with specific category */
        $category = $this->categoryRepository->findOneBy(['slug' => 'categorie-1']);
        $product = new Product;
        $product->setPublicRef('fdsfarezreazreaz')
                ->setPrivateRef('fdsfare')
                ->setDesignation('product with specific category')
                ->setEnDesignation('en designation for product with specific category')
                ->setSlug(
                    'product-with-specific-category'
                )
                ->setEnSlug('product-with-specific-category-en-slug')
                ->setCategory($category)
                ->setPrice(2000)
                ->setStock(0)
                ->setCreatedAt(new DateTimeImmutable())
                ->addSuggestedProduct($this->faker->randomElement($products))
                ;
        $manager->persist($product);

        /*product with all fields*/
        $category = $this->faker->randomElement($categories);
        $subCategory = $category->getSubCategories()->get(1);
        $product = new Product;
        $product->setPublicRef('reareazreaz')
                ->setPrivateRef('reareareareaz')
                ->setDesignation('product with all fields')
                ->setEnDesignation('en designation for product with all fields')
                ->setSlug(
                    'product-with-all-fields'
                )
                ->setEnSlug('product-with-all-fields-en-slug')
                ->setDescription('la description du produit avec tous les champs')
                ->setEnDescription('product with all fields description')
                ->setPrice(2000)
                ->setStock(50)
                ->setCreatedAt(new DateTimeImmutable())
                ->addSuggestedProduct($objet)
                ->addSuggestedProduct($monObjet)
                ->addPicture(
                    (new Picture)
                    ->setListPosition(3)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif 3')
                )
                ->addPicture(
                    (new Picture)
                    ->setListPosition(1)
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setFileName('fichier')
                    ->setFileSize(5)
                    ->setAlt('texte alternatif 1')
                )
                ->setCategory($category)
                ->setSubCategory($subCategory)
                ;
        $manager->persist($product);
        


        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CategoryTestFixtures::class];
    }
}