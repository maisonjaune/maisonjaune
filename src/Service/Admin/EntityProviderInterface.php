<?php

namespace App\Service\Admin;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;

interface EntityProviderInterface
{
    public function getList(Request $request): Collection;
}