<?php

namespace App\Form;


use App\Entity\Address;use App\Entity\Order;use App\Entity\Payment;use Symfony\Bridge\Doctrine\Form\Type\EntityType;use Symfony\Component\Form\AbstractType;use Symfony\Component\Form\FormBuilderInterface;use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('payment',EntityType::class,[
                'class'=>Payment::class,
                'choice_label'=>'name'
            ])
           ->add('address',EntityType::class,[
                'class'=>Address::class,
                'choice_label'=> function ($address) {
                    return $address->getMyCustomTypeField();
                }
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Order::class,
        ]);
    }
}
