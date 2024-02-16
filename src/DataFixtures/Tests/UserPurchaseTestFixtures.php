<?php

namespace App\DataFixtures\Tests;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Purchase;
use App\Config\SiteConfig;
use App\Config\TextConfig;
use App\Entity\PostalDetail;
use App\Entity\PurchaseLine;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Convertor\PurchaseLineProductConvertor;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPurchaseTestFixtures extends Fixture implements DependentFixtureInterface
{
    private Generator $faker;

    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private PurchaseLineProductConvertor $purchaseLineProductConvertor,
        private ProductRepository $productRepository
    )
    {
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager)
    {
        $products = $this->productRepository->findAll();


        //User having purchases in progress
        $user = new User;
        $user
                ->setEmail('user_having_one_purchase_in_progress@gmail.com')
                ->setPassword(
                    $this->hasher->hashPassword($user, 'password')
                )
                ->setCivility(TextConfig::CIVILITY_F)
                ->setFirstName('marie')
                ->setLastName('france')
                ->setRoles([SiteConfig::ROLE_USER])
                ->setCreatedAt(new DateTimeImmutable())
                ;

        $manager->persist($user);

        $postalDetail = (new PostalDetail)
                            ->setCivility(TextConfig::CIVILITY_M)
                            ->setFirstName($this->faker->firstName())
                            ->setLastName($this->faker->lastName())
                            ->setLineOne($this->faker->streetName())
                            ->setCity($this->faker->city())
                            ->setPostcode($this->faker->postcode())
                            ->setCountry($this->faker->country())
                            ->setEnCountry($this->faker->country())
                            ->setCreatedAt(new DateTimeImmutable())
                        ;
        $product = $this->faker->randomElement($products);
        $product2 = $this->faker->randomElement($products);

        $purchase = (new Purchase)
                        ->setRef('ref123456')
                        ->setUser($user)
                        ->setDeliveryDetail($postalDetail)
                        ->setInvoiceDetail($postalDetail)
                        ->setStatus(SiteConfig::STATUS_SENT)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setTotalPrice((2 * $product->getPrice()) + $product2->getPrice())
                        ->addPurchaseLine(
                            (new PurchaseLine)
                            ->setProduct($this->purchaseLineProductConvertor->convert($product))
                            ->setQuantity(2)
                            ->setTotalPrice(2 * $product->getPrice())
                        )
                        ->addPurchaseLine(
                            (new PurchaseLine)
                            ->setProduct($this->purchaseLineProductConvertor->convert($product2))
                            ->setQuantity(1)
                            ->setTotalPrice($product->getPrice())
                        )
                        ->setShippingInfo([
                            'name' => 'Colissimo',
                            'mode' => SiteConfig::DELIVERY_MODE_HOME,
                            'relay' => null,
                            'price' => 450
                        ])
                    ;
        $manager->persist($purchase);


        //user with only terminated purchases
        $user = new User;
        $user
                ->setEmail('user_having_two_terminated_purchases@gmail.com')
                ->setPassword(
                    $this->hasher->hashPassword($user, 'password')
                )
                ->setCivility(TextConfig::CIVILITY_F)
                ->setFirstName('marius')
                ->setLastName('francis')
                ->setRoles([SiteConfig::ROLE_USER])
                ->setCreatedAt(new DateTimeImmutable())
                ;

        $manager->persist($user);

        $postalDetail = (new PostalDetail)
                            ->setCivility(TextConfig::CIVILITY_M)
                            ->setFirstName($this->faker->firstName())
                            ->setLastName($this->faker->lastName())
                            ->setLineOne($this->faker->streetName())
                            ->setCity($this->faker->city())
                            ->setPostcode($this->faker->postcode())
                            ->setCountry($this->faker->country())
                            ->setEnCountry($this->faker->country())
                            ->setCreatedAt(new DateTimeImmutable())
                        ;
        $product = $this->faker->randomElement($products);
        $product2 = $this->faker->randomElement($products);

        $purchase = (new Purchase)
                        ->setRef('ref321654')
                        ->setUser($user)
                        ->setDeliveryDetail($postalDetail)
                        ->setInvoiceDetail($postalDetail)
                        ->setStatus(SiteConfig::STATUS_DELIVERED)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setTotalPrice((2 * $product->getPrice()) + $product2->getPrice())
                        ->addPurchaseLine(
                            (new PurchaseLine)
                            ->setProduct($this->purchaseLineProductConvertor->convert($product))
                            ->setQuantity(2)
                            ->setTotalPrice(2 * $product->getPrice())
                        )
                        ->addPurchaseLine(
                            (new PurchaseLine)
                            ->setProduct($this->purchaseLineProductConvertor->convert($product2))
                            ->setQuantity(1)
                            ->setTotalPrice($product->getPrice())
                        )
                        ->setShippingInfo([
                            'name' => 'Colissimo',
                            'mode' => SiteConfig::DELIVERY_MODE_HOME,
                            'relay' => null,
                            'price' => 450
                        ])
                    ;
        $manager->persist($purchase);

        $purchase = (new Purchase)
                        ->setRef('ref321654')
                        ->setUser($user)
                        ->setDeliveryDetail($postalDetail)
                        ->setInvoiceDetail($postalDetail)
                        ->setStatus(SiteConfig::STATUS_CANCELED)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setTotalPrice(($product->getPrice()))
                        ->addPurchaseLine(
                            (new PurchaseLine)
                            ->setProduct($this->purchaseLineProductConvertor->convert($product))
                            ->setQuantity(1)
                            ->setTotalPrice($product->getPrice())
                        )
                        ->setShippingInfo([
                            'name' => 'Colissimo',
                            'mode' => SiteConfig::DELIVERY_MODE_HOME,
                            'relay' => null,
                            'price' => 450
                        ])
                        ;   
        $manager->persist($purchase);


        //User having purchase pending
        $user = new User;
        $user
                ->setEmail('user_having_one_purchase_pending@gmail.com')
                ->setPassword(
                    $this->hasher->hashPassword($user, 'password')
                )
                ->setCivility(TextConfig::CIVILITY_F)
                ->setFirstName('marie')
                ->setLastName('france')
                ->setRoles([SiteConfig::ROLE_USER])
                ->setCreatedAt(new DateTimeImmutable())
                ;

        $manager->persist($user);

        $postalDetail = (new PostalDetail)
                            ->setCivility(TextConfig::CIVILITY_M)
                            ->setFirstName($this->faker->firstName())
                            ->setLastName($this->faker->lastName())
                            ->setLineOne($this->faker->streetName())
                            ->setCity($this->faker->city())
                            ->setPostcode($this->faker->postcode())
                            ->setCountry($this->faker->country())
                            ->setEnCountry($this->faker->country())
                            ->setCreatedAt(new DateTimeImmutable())
                        ;
        $product = $this->faker->randomElement($products);
        $product2 = $this->faker->randomElement($products);

        $purchase = (new Purchase)
                        ->setRef('ref123456')
                        ->setUser($user)
                        ->setDeliveryDetail($postalDetail)
                        ->setInvoiceDetail($postalDetail)
                        ->setStatus(SiteConfig::STATUS_PENDING)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setTotalPrice((2 * $product->getPrice()) + $product2->getPrice())
                        ->addPurchaseLine(
                            (new PurchaseLine)
                            ->setProduct($this->purchaseLineProductConvertor->convert($product))
                            ->setQuantity(2)
                            ->setTotalPrice(2 * $product->getPrice())
                        )
                        ->addPurchaseLine(
                            (new PurchaseLine)
                            ->setProduct($this->purchaseLineProductConvertor->convert($product2))
                            ->setQuantity(1)
                            ->setTotalPrice($product->getPrice())
                        )
                        ->setShippingInfo([
                            'name' => 'Colissimo',
                            'mode' => SiteConfig::DELIVERY_MODE_HOME,
                            'relay' => null,
                            'price' => 450
                        ])
                    ;
        $manager->persist($purchase);


        $manager->flush();
        
    }


    public function getDependencies()
    {
        return [ProductTestFixtures::class];
    }
}
       