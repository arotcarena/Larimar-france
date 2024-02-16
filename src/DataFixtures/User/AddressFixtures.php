<?php
namespace App\DataFixtures\User;

use Faker\Factory;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Address;
use App\Config\TextConfig;
use App\Repository\UserRepository;
use App\DataFixtures\User\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;



class AddressFixtures extends Fixture implements DependentFixtureInterface, FixtureGroupInterface
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
        private UserRepository $userRepository
    )
    {
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager)
    {
        $users = $this->userRepository->findAll();

        foreach($users as $user)
        {
            $iso = $this->faker->randomElement(array_keys($this->countries));
            $address = (new Address)
                        ->setUser($user)
                        ->setCivility($this->faker->randomElement([TextConfig::CIVILITY_M, TextConfig::CIVILITY_F]))
                        ->setFirstName($this->faker->firstName())
                        ->setLastName($this->faker->lastName())
                        ->setLineOne($this->faker->streetAddress())
                        ->setPostcode($this->faker->postcode())
                        ->setCity($this->faker->city())
                        ->setCountry($this->countries[$iso]['fr'])
                        ->setEnCountry($this->countries[$iso]['en'])
                        ->setIso($iso)
                        ->setCreatedAt(new DateTimeImmutable())
                    ;
            $manager->persist($address);
        }

        for ($i=0; $i < 10; $i++) { 
            $iso = $this->faker->randomElement(array_keys($this->countries));
            $address = (new Address)
                        ->setUser($this->faker->randomElement($users))
                        ->setCivility($this->faker->randomElement([TextConfig::CIVILITY_M, TextConfig::CIVILITY_F]))
                        ->setFirstName($this->faker->firstName())
                        ->setLastName($this->faker->lastName())
                        ->setLineOne($this->faker->streetAddress())
                        ->setPostcode($this->faker->postcode())
                        ->setCity($this->faker->city())
                        ->setCountry($this->countries[$iso]['fr'])
                        ->setEnCountry($this->countries[$iso]['en'])
                        ->setIso($iso)
                        ->setCreatedAt(new DateTimeImmutable())
                    ;
            $manager->persist($address);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
    
}