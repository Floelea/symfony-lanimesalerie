<?php

namespace App\Form;

use App\Entity\ProductCategory;use App\Entity\ProductSubCategory;use Symfony\Bridge\Doctrine\Form\Type\EntityType;use Symfony\Component\Form\AbstractType;use Symfony\Component\Form\FormBuilderInterface;use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductSubCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('productCategory', EntityType::class,[
                'class'=>ProductCategory::class,
                'choice_label'=>'name'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductSubCategory::class,
        ]);
    }
}
