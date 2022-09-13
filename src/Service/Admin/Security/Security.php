<?php

namespace App\Service\Admin\Security;

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
        return $this->authorizationChecker->isGranted(sprintf('ROLE_ADMIN_%s_%s', strtoupper($this->type), strtoupper($name)));
    }
}