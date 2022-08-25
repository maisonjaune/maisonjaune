<?php

namespace App\Form\Security;

use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordForgetType extends AbstractType
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class)
        ;

        $builder->addModelTransformer(new CallbackTransformer(
            fn () => null,
            fn ($data) => $this->userRepository->findOneByEmail($data['email'])
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
    }
}
