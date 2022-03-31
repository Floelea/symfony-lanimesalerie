<?php

namespace App\Form;

use App\Entity\AnimalCategory;
use App\Entity\Product;
use App\Entity\ProductCategory;
use App\Entity\ProductSubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('animalCategory',EntityType::class,[
                'class'=>AnimalCategory::class,
                'choice_label'=>'name',
            ])
            ->add('productCategory',EntityType::class,[
                'class'=>ProductCategory::class,
                'choice_label'=>'name',
            ])
            ->add('productSubCategory',EntityType::class,[
                'class'=>ProductSubCategory::class,
                'choice_label'=>'name'
            ])
            ->add('priceHt')
            ->add('name')
            ->add('description')
        ->add('images',CollectionType::class,[
        'entry_type'=>ImageType::class,
        'allow_add'=>true,
        'allow_delete'=>true,
        'required'=>false,
        'by_reference'=>false,
        'disabled'=>false,
        'prototype'=>true,
        'label'=>false
    ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
