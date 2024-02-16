<?php
namespace App\DataFixtures\Tests;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use DateTimeImmutable;
use App\Entity\Address;
use App\Config\SiteConfig;
use App\Config\TextConfig;
use App\Repository\UserRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class UserWithMaxAddressesTestFixtures extends Fixture
{
    private Generator $faker;

    public function __construct(
        private UserRepository $userRepository
    )
    {
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager)
    {
        //user with max addresses
        $user = new User;
        $user
            ->setEmail('user_with_max_addresses@gmail.com')
            ->setPassword('password')
            ->setRoles([SiteConfig::ROLE_USER])
            ->setConfirmed(true)
            ->setCreatedAt(new DateTimeImmutable())
            ;
        $manager->persist($user);

        for ($i=0; $i < SiteConfig::MAX_REGISTERED_ADDRESSES_PER_USER; $i++) { 
            $address = (new Address)
                        ->setUser($user)
                        ->setCivility($this->faker->randomElement([TextConfig::CIVILITY_M, TextConfig::CIVILITY_F]))
                        ->setFirstName($this->faker->firstName())
                        ->setLastName($this->faker->lastName())
                        ->setLineOne($this->faker->streetAddress())
                        ->setPostcode($this->faker->postcode())
                        ->setCity($this->faker->city())
                        ->setCountry($this->faker->country())
                        ->setEnCountry($this->faker->country())
                        ->setCreatedAt(new DateTimeImmutable())
                    ;
            $manager->persist($address);
        }



        //user with max less one addresses
        $userLess = new User;
        $userLess
            ->setEmail('user_with_max_less_one_addresses@gmail.com')
            ->setPassword('password')
            ->setRoles([SiteConfig::ROLE_USER])
            ->setConfirmed(true)
            ->setCreatedAt(new DateTimeImmutable())
            ;
        $manager->persist($userLess);

        for ($i=0; $i < (SiteConfig::MAX_REGISTERED_ADDRESSES_PER_USER - 1); $i++) { 
            $address = (new Address)
                        ->setUser($userLess)
                        ->setCivility($this->faker->randomElement([TextConfig::CIVILITY_M, TextConfig::CIVILITY_F]))
                        ->setFirstName($this->faker->firstName())
                        ->setLastName($this->faker->lastName())
                        ->setLineOne($this->faker->streetAddress())
                        ->setPostcode($this->faker->postcode())
                        ->setCity($this->faker->city())
                        ->setCountry($this->faker->country())
                        ->setEnCountry($this->faker->country())
                        ->setCreatedAt(new DateTimeImmutable())
                    ;
            $manager->persist($address);
        }

        $manager->flush();
    }
    
}