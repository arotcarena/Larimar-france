<?php

namespace App\Form;

use App\DataTransformer\PriceTransformer;
use App\Entity\DeliveryMethod;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryMethodType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deliveryTime', NumberType::class, [
                'required' => false
            ])
            ->add('price', NumberType::class)
        ;

        $builder->get('price')->addModelTransformer(new PriceTransformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DeliveryMethod::class,
        ]);
    }
}
