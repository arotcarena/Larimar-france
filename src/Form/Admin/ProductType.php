<?php

namespace App\Form\Admin;

use App\Config\SiteConfig;
use App\DataTransformer\PriceTransformer;
use App\Entity\Product;
use App\Entity\Category;
use App\Entity\SubCategory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\NotNull;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pictureOne', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File(
                        extensions: ['jpg', 'png'],
                        extensionsMessage: 'Format requis : jpg, png',
                        maxSize: '2M',
                        maxSizeMessage: 'Image trop lourde. Maximum 2 Mo'
                    ),
                    new NotNull(message: 'La photo principale est obligatoire', groups: ['create'])
                ]
            ])
            ->add('altOne', TextType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('pictureTwo', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        extensions: ['jpg', 'png'],
                        extensionsMessage: 'Format requis : jpg, png',
                        maxSize: '2000k',
                        maxSizeMessage: 'Image trop lourde. Maximum 2000k'
                    )
                ]
            ])
            ->add('altTwo', TextType::class, [
                'mapped' => false,
                'required' => false
            ])
            ->add('pictureThree', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File(
                        extensions: ['jpg', 'png'],
                        extensionsMessage: 'Format requis : jpg, png',
                        maxSize: '2000k',
                        maxSizeMessage: 'Image trop lourde. Maximum 2000k'
                    )
                ]
            ])
            ->add('altThree', TextType::class, [
                'mapped' => false,
                'required' => false
            ])

            ->add('publicRef', TextType::class)
            ->add('privateRef', TextType::class, [
                'required' => false
            ])
            ->add('designation', TextType::class)
            ->add('enDesignation', TextType::class)
            
            ->add('slug', TextType::class)
            ->add('enSlug', TextType::class)

            ->add('description', TextareaType::class, [
                'required' => false
            ])
            ->add('enDescription', TextareaType::class, [
                'required' => false
            ])
            ->add('metaDescription', TextareaType::class, [
                'required' => false
            ])
            ->add('enMetaDescription', TextareaType::class, [
                'required' => false
            ])

            ->add('totalDimension', NumberType::class, [
                'required' => false
            ])
            ->add('cabochonDimension', NumberType::class, [
                'required' => false
            ])
            ->add('weight', NumberType::class, [
                'required' => false
            ])
            ->add('fingerSize', NumberType::class, [
                'required' => false
            ])
            ->add('color', TextType::class, [
                'required' => false
            ])
            ->add('enColor', TextType::class, [
                'required' => false
            ])
            ->add('material', ChoiceType::class, [
                'required' => false,
                'choices' => SiteConfig::PRODUCT_MATERIALS
            ])

            ->add('price', NumberType::class)
            ->add('stock', NumberType::class)

            ->add('category', EntityType::class, [
                'required' => false,
                'class' => Category::class,
                'choice_label' => 'name'
                ])
            ->add('subCategory', EntityType::class, [
                'required' => false,
                'class' => SubCategory::class,
                'choice_label' => 'name'
                ])

            ->add('suggestedProducts', EntityType::class, [
                'required' => false,
                'multiple' => true,
                'class' => Product::class,
                'choice_label' => 'designation'
            ])
            ;

        $builder->get('price')->addModelTransformer(new PriceTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class
        ]);
    }
}
