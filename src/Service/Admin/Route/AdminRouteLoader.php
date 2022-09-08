<?php

namespace App\Service\Admin\Route;

use App\Service\Admin\AdminCRUDInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\RouteCollection;

class AdminRouteLoader extends Loader
{
    /**
     * @var Collection<AdminCRUDInterface>
     */
    private Collection $admins;

    private const ROUTE_TYPE = 'admin';

    public function __construct(string $env = null)
    {
        parent::__construct($env);
        $this->admins = new ArrayCollection();
    }

    public function addAdminCrud(AdminCRUDInterface $admin)
    {
        $this->admins->add($admin);
    }

    public function load(mixed $resource, string $type = null)
    {
        $routes = new RouteCollection();

        foreach ($this->admins as $admin) {
            $routes->addCollection($admin->getRouter()->createRouteCollection());
        }

        return $routes;
    }

    public function supports(mixed $resource, string $type = null)
    {
        return self::ROUTE_TYPE === $type;
    }
}