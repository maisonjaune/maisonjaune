<?php

namespace App\EventListener\User;

use App\Entity\User;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HasChangedPasswordListener
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    public function prePersist(User $user)
    {
        $this->checkPassword($user);
    }

    public function preUpdate(User $user)
    {
        $this->checkPassword($user);
    }

    private function checkPassword(User $user)
    {
        if (null !== $user->getPlainPassword()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, $user->getPlainPassword()));
        }
    }
}