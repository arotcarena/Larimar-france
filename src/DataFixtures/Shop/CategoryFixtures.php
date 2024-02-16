<?php
namespace App\DataFixtures\Shop;

use App\Entity\Category;
use App\Entity\SubCategory;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;



class CategoryFixtures extends Fixture implements FixtureGroupInterface
{



    public function load(ObjectManager $manager)
    {
        $category = (new Category)
                    ->setName('Bijoux Femme')
                    ->setEnName('Women Jewelry')
                    ->setSlug('bijoux-femme')
                    ->setEnSlug('women-jewelry')
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Bracelets')
                        ->setEnName('Bangles')
                        ->setSlug('bracelets')
                        ->setEnSlug('bangles')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(1)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Boucles d\'oreilles')
                        ->setEnName('Earings')
                        ->setSlug('boucles-d-oreilles')
                        ->setEnSlug('earings')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(2)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Bagues')
                        ->setEnName('Rings')
                        ->setSlug('bagues')
                        ->setEnSlug('rings')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(3)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Colliers')
                        ->setEnName('Necklaces')
                        ->setSlug('colliers')
                        ->setEnSlug('necklaces')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(3)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Pendentifs')
                        ->setEnName('Pendants')
                        ->setSlug('pendentifs')
                        ->setEnSlug('pendants')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(4)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Accessoires')
                        ->setEnName('Accessories')
                        ->setSlug('accessoires')
                        ->setEnSlug('accessories')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(4)
                    )
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setListPosition(1);
                    ;
        $manager->persist($category);
        
        $category = (new Category)
                    ->setName('Bijoux Homme')
                    ->setEnName('Men\'s Jewelry')
                    ->setSlug('bijoux-homme')
                    ->setEnSlug('men-s-jewelry')
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Bracelets')
                        ->setEnName('Bangles')
                        ->setSlug('bracelets')
                        ->setEnSlug('bangles')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(1)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Boucles d\'oreilles')
                        ->setEnName('Earings')
                        ->setSlug('boucles-d-oreilles')
                        ->setEnSlug('earings')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(2)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Bagues')
                        ->setEnName('Rings')
                        ->setSlug('bagues')
                        ->setEnSlug('rings')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(3)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Colliers')
                        ->setEnName('Necklaces')
                        ->setSlug('colliers')
                        ->setEnSlug('necklaces')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(3)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Pendentifs')
                        ->setEnName('Pendants')
                        ->setSlug('pendentifs')
                        ->setEnSlug('pendants')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(4)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Accessoires')
                        ->setEnName('Accessories')
                        ->setSlug('accessoires')
                        ->setEnSlug('accessories')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(4)
                    )
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setListPosition(2);
                    ;
        $manager->persist($category);

        $category = (new Category)
                    ->setName('Bijoux Enfant')
                    ->setEnName('Children\'s Jewelry')
                    ->setSlug('bijoux-enfant')
                    ->setEnSlug('children-s-jewelry')
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Bracelets')
                        ->setEnName('Bangles')
                        ->setSlug('bracelets')
                        ->setEnSlug('bangles')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(1)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Boucles d\'oreilles')
                        ->setEnName('Earings')
                        ->setSlug('boucles-d-oreilles')
                        ->setEnSlug('earings')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(2)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Bagues')
                        ->setEnName('Rings')
                        ->setSlug('bagues')
                        ->setEnSlug('rings')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(3)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Colliers')
                        ->setEnName('Necklaces')
                        ->setSlug('colliers')
                        ->setEnSlug('necklaces')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(3)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Pendentifs')
                        ->setEnName('Pendants')
                        ->setSlug('pendentifs')
                        ->setEnSlug('pendants')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(4)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Accessoires')
                        ->setEnName('Accessories')
                        ->setSlug('accessoires')
                        ->setEnSlug('accessories')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(4)
                    )
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setListPosition(3);
                    ;
        $manager->persist($category);

        $category = (new Category)
                    ->setName('Décoration & Détente')
                    ->setEnName('Decoration & Relaxation')
                    ->setSlug('decoration-et-detente')
                    ->setEnSlug('decoration-and-relaxation')
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Décoration')
                        ->setEnName('Decoration')
                        ->setSlug('decoration')
                        ->setEnSlug('decoration')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(1)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Détente')
                        ->setEnName('Relaxation')
                        ->setSlug('detente')
                        ->setEnSlug('relaxation')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(2)
                    )
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setListPosition(4);
                    ;
        $manager->persist($category);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}