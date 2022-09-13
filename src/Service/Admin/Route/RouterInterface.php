<?php

namespace App\Service\Admin\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

interface RouterInterface
{
    public function getRoute(string $name): Route;

    public function addRoute(string $name, string $path, array $methods = [Request::METHOD_GET]): self;

    public function removeRoute(string $name): self;

    public function createRouteCollection(): RouteCollection;
}