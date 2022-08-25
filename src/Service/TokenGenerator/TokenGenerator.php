<?php

namespace App\Service\TokenGenerator;

use App\Entity\Token;
use App\Entity\User;
use App\Enum\Token\TypeEnum;

class TokenGenerator implements TokenGeneratorInterface
{
    public function generate(User $user, TypeEnum $type, string $duration = 'PT1H'): Token
    {
        $date = new \DateTimeImmutable();
        $date->add(new \DateInterval($duration));

        $key = sha1(random_bytes(12));

        return (new Token())
            ->setValue($key)
            ->setUser($user)
            ->setType($type)
            ->setExpireAt($date);
    }
}