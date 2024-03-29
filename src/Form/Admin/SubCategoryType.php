<?php

namespace App\Form\Admin;

use App\Entity\Category;
use App\Entity\SubCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SubCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('picture', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File(
                        extensions: ['jpg', 'png'],
                        extensionsMessage: 'Format requis : jpg, png',
                        maxSize: '2M',
                        maxSizeMessage: 'Image trop lourde. Maximum 2 Mo'
                    ),
                    new NotNull(message: 'La photo est obligatoire', groups: ['create'])
                ]
            ])
            ->add('alt', TextType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('parentCategory', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name'
            ])
            ->add('name', TextType::class)
            ->add('enName', TextType::class)
            ->add('slug', TextType::class)
            ->add('enSlug', TextType::class)
            ->add('listPosition', NumberType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SubCategory::class
        ]);
    }
}
