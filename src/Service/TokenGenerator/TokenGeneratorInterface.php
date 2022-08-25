<?php

namespace App\Service\TokenGenerator;

use App\Entity\Token;
use App\Entity\User;
use App\Enum\Token\TypeEnum;

interface TokenGeneratorInterface
{
    public function generate(User $user, TypeEnum $type, string $duration = 'PT1H'): Token;
}