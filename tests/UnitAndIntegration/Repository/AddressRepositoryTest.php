<?php
namespace App\Tests\UnitAndIntegration\Repository;

use App\Config\SiteConfig;
use App\DataFixtures\Tests\UserWithMaxAddressesTestFixtures;
use App\Repository\AddressRepository;
use App\Repository\UserRepository;
use App\Tests\Utils\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AddressRepositoryTest extends KernelTestCase
{
    use FixturesTrait;

    private AddressRepository $addressRepository;


    public function setUp(): void
    {
        parent::setUp();

        self::bootKernel();

        $this->addressRepository = static::getContainer()->get(AddressRepository::class);

        $this->loadFixtures([UserWithMaxAddressesTestFixtures::class]);
    } 

    public function testCountByUser()
    {
        $user = $this->findEntity(UserRepository::class, ['email' => 'user_with_max_addresses@gmail.com']); // user with max addresses
        $this->assertEquals(
            SiteConfig::MAX_REGISTERED_ADDRESSES_PER_USER,
            $this->addressRepository->countByUser($user)
        );

        $user = $this->findEntity(UserRepository::class, ['email' => 'user_with_max_less_one_addresses@gmail.com']); // user with max less one addresses
        $this->assertEquals(
            SiteConfig::MAX_REGISTERED_ADDRESSES_PER_USER - 1,
            $this->addressRepository->countByUser($user)
        );
    }
}