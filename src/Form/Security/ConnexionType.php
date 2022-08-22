<?php

namespace App\Form\Security;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO Integration du Recaptcha
        $authenticationUtils = $this->getAuthenticationUtils($options);

        $builder
            ->add('email', EmailType::class, [
                'data' => null !== $authenticationUtils ? $authenticationUtils->getLastUsername() : null,
                'attr' => [
                    'autofocus' => true,
                ],
            ])
            ->add('password', PasswordType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_token_id'   => 'authenticate',
        ]);

        $resolver->setDefined('authenticationUtils');
        $resolver->setAllowedTypes('authenticationUtils', AuthenticationUtils::class);
    }

    /**
     * @param array $options
     * @return AuthenticationUtils|null
     */
    private function getAuthenticationUtils(array $options): ?AuthenticationUtils
    {
        return $options['authenticationUtils'] ?? null;
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
