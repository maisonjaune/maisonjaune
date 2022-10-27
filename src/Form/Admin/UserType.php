<?php

namespace App\Form\Admin;

use App\Entity\User;
use App\Enum\CivilityEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('civility', EnumType::class, [
                'class' => CivilityEnum::class
            ])
            ->add('firstname')
            ->add('lastname')
            ->add('email')
            ->add('isActif')
            ->add('isMember')
            ->add('lastConnectionAt')
            ->add('password')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
