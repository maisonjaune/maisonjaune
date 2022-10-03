<?php

namespace App\Service\Admin\Route;

use App\Service\Admin\Exception\UndefinedRouteException;
use App\Service\Admin\Utils\RouteNameGenerator;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

class Router implements RouterInterface
{
    private Collection $routes;

    public function __construct(private string $type, private string $controllerClass)
    {
        $this->routes = new ArrayCollection();
    }

    public function hasRoute(string $name): bool
    {
        return $this->routes->containsKey($name);
    }

    public function getRoute(string $name): Route
    {
        if (!$this->hasRoute($name)) {
            throw new UndefinedRouteException($name);
        }

        return $this->routes->get($name);
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
        $this->routes->set($name, $this->createRoute(RouteNameGenerator::generate($this->type, $name), $path, $name, $methods));

        return $this;
    }

    public function removeRoute(string $name): self
    {
        $this->routes->remove($name);

        return $this;
    }

    public function createRouteCollection(): RouteCollection
    {
        $routeCollection = new RouteCollection();

        foreach ($this->routes as $route) {
            $routeCollection->add($route->getName(), $route);
        }

        return $routeCollection;
    }

    protected function createRoute(string $name, string $path, string $action, array $methods = [Request::METHOD_GET]): Route
    {
        return (new Route(sprintf('/admin%s', $path)))
            ->setName($name)
            ->setMethods($methods)
            ->setDefault('_controller', $this->controllerClass . '::' . $action);
    }
}