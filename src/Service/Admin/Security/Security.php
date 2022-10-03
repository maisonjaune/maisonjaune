<?php

namespace App\Service\Admin\Security;

use App\Service\Admin\Utils\RoleNameGenerator;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class Security
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker,
        private string                                 $type,
    )
    {
    }

    public function can(string $name)
    {
        return $this->authorizationChecker->isGranted(RoleNameGenerator::generate($this->type, $name));
    }
}