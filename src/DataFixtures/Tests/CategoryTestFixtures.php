<?php

namespace App\DataFixtures\Tests;

use DateTimeImmutable;
use App\Entity\Picture;
use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategoryTestFixtures extends Fixture
{
   
    public function load(ObjectManager $manager)
    {
        $category = (new Category)
                    ->setName('Catégorie 1')
                    ->setEnName('Category 1')
                    ->setSlug('categorie-1')
                    ->setEnSlug('category-1')
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Sous-catégorie 1')
                        ->setEnName('Subcategory 1')
                        ->setSlug('sous-categorie-1')
                        ->setEnSlug('sub-category-1')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(2)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Sous-catégorie 2')
                        ->setEnName('Subcategory 2')
                        ->setSlug('sous-categorie-2')
                        ->setEnSlug('sub-category-2')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(3)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Sous-catégorie 3')
                        ->setEnName('Subcategory 3')
                        ->setSlug('sous-categorie-3')
                        ->setEnSlug('sub-category-3')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(1)
                        ->setPicture(
                            (new Picture)
                            ->setFileName('file')
                            ->setAlt('texte alternatif de la sous-catégorie 3')
                            ->setCreatedAt(new DateTimeImmutable())
                        )
                    )
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setListPosition(2);
                    ;
        $manager->persist($category);

        $category = (new Category)
                    ->setName('Catégorie 2')
                    ->setEnName('Category 2')
                    ->setSlug('categorie-2')
                    ->setEnSlug('category-2')
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Sous-catégorie 1')
                        ->setEnName('Subcategory 1')
                        ->setSlug('sous-categorie-1')
                        ->setEnSlug('sub-category-1')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(2)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Sous-catégorie 2')
                        ->setEnName('Subcategory 2')
                        ->setSlug('sous-categorie-2')
                        ->setEnSlug('sub-category-2')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(1)
                    )
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setListPosition(3);
                    ;
        $manager->persist($category);


        $category = (new Category)
                    ->setName('Catégorie 3')
                    ->setEnName('Category 3')
                    ->setSlug('categorie-3')
                    ->setEnSlug('category-3')
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Sous-catégorie 1')
                        ->setEnName('Subcategory 1')
                        ->setSlug('sous-categorie-1')
                        ->setEnSlug('sub-category-1')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(1)
                    )
                    ->addSubCategory(
                        (new SubCategory)
                        ->setName('Sous-catégorie 2')
                        ->setEnName('Subcategory 2')
                        ->setSlug('sous-categorie-2')
                        ->setEnSlug('sub-category-2')
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setListPosition(2)
                    )
                    ->setPicture(
                        (new Picture)
                        ->setCreatedAt(new DateTimeImmutable())
                        ->setFileName('fichier')
                        ->setFileSize(5)
                        ->setAlt('texte alternatif de la picture de la categorie 3')
                    )
                    ->setCreatedAt(new DateTimeImmutable())
                    ->setListPosition(1);
                    ;
        $manager->persist($category);

        $manager->flush();
    }
}