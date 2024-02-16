<?php
namespace App\DataFixtures\Customer;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Purchase;
use App\Config\SiteConfig;
use App\Config\TextConfig;
use App\Entity\PostalDetail;
use App\Entity\PurchaseLine;
use App\Repository\UserRepository;
use App\Repository\ProductRepository;
use App\DataFixtures\User\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use App\DataFixtures\Shop\ProductFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Convertor\PurchaseLineProductConvertor;
use App\Entity\DeliveryMethod;
use App\Repository\DeliveryMethodRepository;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class PurchaseFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
{
    private Generator $faker;
    private $countries = [
        'FR' => [
            'fr' => 'France',
            'en' => 'France'
        ],
        'ES' => [
            'fr' => 'Espagne',
            'en' => 'Spain'    
        ],
        'IT' => [
            'fr' => 'Italie',
            'en' => 'Italy'    
        ],
        'PT' => [
            'fr' => 'Portugal',
            'en' => 'Portugal'   
        ],
        'DE' => [
            'fr' => 'Allemagne',
            'en' => 'Germany'    
        ],
        'US' => [
            'fr' => 'Etats-Unis',
            'en' => 'United States'
            ],
        'RU' => [
            'fr' => 'Russie',
            'en' => 'Russia'    
        ],
        'NL' => [
            'fr' => 'Pays-Bas',
            'en' => 'Netherlands'
            ]
    ];


    public function __construct(
        private UserRepository $userRepository,
        private ProductRepository $productRepository,
        private PurchaseLineProductConvertor $purchaseLineProductConvertor,
        private DeliveryMethodRepository $deliveryMethodRepository
    )
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();
        $products = $this->productRepository->findAll();
        $deliveryMethods = $this->deliveryMethodRepository->findAll();


        /** @var Purchase[] */
        $purchases = [];
        for ($i=0; $i < 100; $i++) { 
            /** @var User */
            $user = $this->faker->randomElement($users);
            if(!$user->getCivility() || !$user->getFirstName() || !$user->getLastName())
            {
                $user->setCivility($this->faker->randomElement([TextConfig::CIVILITY_F, TextConfig::CIVILITY_M]))
                        ->setFirstName($this->faker->firstName())
                        ->setLastName($this->faker->lastName())
                        ;
            }
            $createdAt = new DateTimeImmutable(($this->faker->dateTime('now', 'Europe/Paris'))->format('Y:m:d H:h:i'));

            
            $iso = $this->faker->randomElement(array_keys($this->countries));
            $postalDetail = (new PostalDetail)
                                ->setCivility($this->faker->randomElement([TextConfig::CIVILITY_M, TextConfig::CIVILITY_F]))
                                ->setFirstName($this->faker->firstName())
                                ->setLastName($this->faker->lastName())
                                ->setLineOne($this->faker->streetName())
                                ->setCity($this->faker->city())
                                ->setPostcode($this->faker->postcode())
                                ->setCountry($this->countries[$iso]['fr'])
                                ->setEnCountry($this->countries[$iso]['en'])
                                ->setIso($iso)
                                ->setCreatedAt($createdAt)
                            ;

            /** @var DeliveryMethod */
            $deliveryMethod = $this->faker->randomElement($deliveryMethods);

            $mode = $this->faker->randomElement([SiteConfig::DELIVERY_MODE_HOME, SiteConfig::DELIVERY_MODE_RELAY]);
            if($mode === SiteConfig::DELIVERY_MODE_HOME)
            {
                $relay = null;                
            }
            else
            {
                $relay = [
                    'id' => $iso.'_'.substr(str_shuffle(str_repeat('0123456789', 5)), 0, 6),
                    'name' => $this->faker->randomElement(['Intermarché', 'Le fournil de Roger', 'Tabac de la gare', 'Le terminus', 'Kebab chez Momo']),
                    'lineOne' => $this->faker->streetAddress(),
                    'lineTwo' => null,
                    'postcode' => $this->faker->postcode(),
                    'city' => $this->faker->city()
                ];
            }

            $purchase = (new Purchase)
                            ->setRef(substr(str_shuffle(str_repeat('azerttyuiopqsdfghjklmwxcvbn0123456789', 5)), 0, 8))
                            ->setUser($user)
                            ->setDeliveryDetail($postalDetail)
                            ->setShippingInfo([
                                'name' => $deliveryMethod->getName(),
                                'mode' => $mode,
                                'relay' => $relay,
                                'price' => $deliveryMethod->getPrice()
                            ])
                            ->setInvoiceDetail($postalDetail)
                            ->setStatus(
                                $this->faker->randomElement([
                                    SiteConfig::STATUS_PENDING, SiteConfig::STATUS_PAID, SiteConfig::STATUS_SENT, SiteConfig::STATUS_DELIVERED, SiteConfig::STATUS_CANCELED
                                ])
                            )
                            ->setCreatedAt($createdAt)
                        ;
            if($purchase->getStatus() !== SiteConfig::STATUS_PENDING)
            {
                $purchase->setPaidAt(new DateTimeImmutable());
            }
            $purchases[] = $purchase;
        }

        //ajout d'une purchaseLine pour chaque Purchase
        foreach($purchases as $purchase)
        {
            $product = $this->faker->randomElement($products);
            $quantity = random_int(1, 3);

            $purchaseLine = (new PurchaseLine)
                                ->setProduct($this->purchaseLineProductConvertor->convert($product))
                                ->setQuantity($quantity)
                                ->setTotalPrice($product->getPrice() * $quantity)
                            ;
            $purchase->addPurchaseLine($purchaseLine)
                    ->setTotalPrice($purchase->getTotalPrice() + $purchaseLine->getTotalPrice())
                    ;
            $manager->persist($purchase);
        }
        // ajout aléatoire de purchaseLines
        for ($i=0; $i < 10; $i++) { 
            $product = $this->faker->randomElement($products);
            $quantity = random_int(1, 3);

            $purchaseLine = (new PurchaseLine)
                                ->setProduct($this->purchaseLineProductConvertor->convert($product))
                                ->setQuantity($quantity)
                                ->setTotalPrice($product->getPrice() * $quantity)
                            ;
            /** @var Purchase */
            $purchase = $this->faker->randomElement($purchases);
            $purchase->addPurchaseLine($purchaseLine)
                    ->setTotalPrice($purchase->getTotalPrice() + $purchaseLine->getTotalPrice())
                    ;
        }



        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class, ProductFixtures::class, DeliveryMethodFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}