<?php

namespace App\Service\Admin;

use App\Service\Admin\Exception\UndefinedRouteException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class Router implements RouterInterface
{
    private array $routes = [];

    private array $routeNames = [];

    public function __construct(private string $type, private string $controllerClass)
    {
    }

    public function hasRoute(string $name): bool
    {
        return isset($this->routes[$name]);
    }

    public function getRoute(string $name): Route
    {
        if (!$this->hasRoute($name)) {
            throw new UndefinedRouteException($name);
        }

        return $this->routes[$name];
    }

    public function getRouteName(string $name): ?string
    {
        return $this->routeNames[$name] ?? null;
    }

    public function addRouteIndex(string $path, array $methods = [Request::METHOD_GET]): self
    {
        return $this->addRoute('index', $path, $methods);
    }

    public function addRouteNew(string $path, array $methods = [Request::METHOD_GET, Request::METHOD_POST]): self
    {
        return $this->addRoute('new', $path, $methods);
    }

    public function addRouteShow(string $path, array $methods = [Request::METHOD_GET]): self
    {
        return $this->addRoute('show', $path, $methods);
    }

    public function addRouteEdit(string $path, array $methods = [Request::METHOD_GET, Request::METHOD_POST]): self
    {
        return $this->addRoute('edit', $path, $methods);
    }

    public function addRouteDelete(string $path, array $methods = [Request::METHOD_GET, Request::METHOD_POST]): self
    {
        return $this->addRoute('delete', $path, $methods);
    }

    public function addRoute(string $name, string $path, array $methods = [Request::METHOD_GET]): self
    {
        $this->routeNames[$name] = sprintf('admin_%s_%s', $this->type, $name);
        $this->routes[$name] = $this->createRoute($path, $name, $methods);

        return $this;
    }

    public function removeRoute(string $name): self
    {
        unset($this->routeNames[$name]);
        unset($this->routes[$name]);

        return $this;
    }

    public function createRouteCollection(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        foreach ($this->routes as $name => $route) {
            $routeCollection->add($this->routeNames[$name], $route);
        }

        return $routeCollection;
    }

    protected function createRoute(string $path, string $action, array $methods = [Request::METHOD_GET]): Route
    {
        return (new Route(sprintf('/admin%s', $path)))
            ->setMethods($methods)
            ->setDefault('_controller', $this->controllerClass . '::' . $action);
    }
}