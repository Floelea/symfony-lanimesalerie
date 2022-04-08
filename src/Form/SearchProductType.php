<?php

namespace App\Form;

use App\Entity\AnimalCategory;
use App\Entity\ProductCategory;
use App\Entity\ProductSubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchProductType extends AbstractType
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
