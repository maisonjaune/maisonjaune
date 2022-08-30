<?php

namespace App\Service\Admin;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;

interface FilterRepositoryInterface
{
    public function getQueryFilter(Request $request, ?array $parameters = null): Query;
}