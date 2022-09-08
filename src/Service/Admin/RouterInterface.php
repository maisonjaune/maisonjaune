<?php

namespace App\Service\Admin;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

interface RouterInterface
{
    public function getRoute(string $name): Route;

    public function getRouteName(string $name): ?string;

    public function createRouteCollection(): RouteCollection;
}