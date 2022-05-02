<?php

namespace App\Form;

use App\Entity\Address;
use App\Entity\Gender;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;use Symfony\Component\Form\Extension\Core\Type\BirthdayType;use Symfony\Component\Form\FormBuilderInterface;use Symfony\Component\OptionsResolver\OptionsResolver;

class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('gender',EntityType::class,[
                'class'=>Gender::class,
                'choice_label'=>'gender',
            ])
            ->add('email')
            ->add('username')
            ->add('name')
            ->add('lastName')
            ->add('birthDay',BirthdayType::class,[
            'format'=>'dMy'
        ]);
//            ->add('address',EntityType::class,[
//                'class'=>Address::class
//            ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
