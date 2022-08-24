<?php

namespace App\Menu\Admin;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MainMenuBuilder
{
    public function __construct(private FactoryInterface $factory)
    {
    }

    public function createSidebarMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('Dashboard', ['route' => 'app_admin_dashboard']);

        return $menu;
    }
}