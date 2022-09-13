<?php

namespace App\Service\Admin\Route;

class RouterFactory
{
    public function create(string $routerPrefix, string $controllerClass): RouterInterface
    {
        return (new Router($routerPrefix, $controllerClass))
            ->addRouteIndex(sprintf('/%s', $routerPrefix))
            ->addRouteNew(sprintf('/%s/new', $routerPrefix))
            ->addRouteShow(sprintf('/%s/{id}', $routerPrefix))
            ->addRouteEdit(sprintf('/%s/{id}/edit', $routerPrefix))
            ->addRouteDelete(sprintf('/%s/{id}/delete', $routerPrefix));
    }
}