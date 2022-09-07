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
        $menu->addChild('User List', ['route' => 'app_admin_user_index']);

        return $menu;
    }

    public function createtopbarMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root', [
            'childrenAttributes' => [
                'class' => 'navbar-nav ml-auto'
            ],
        ]);

        $action = $menu->addChild('Action', [
            'uri' => '#',
            'childrenAttributes' => [
                'class' => 'dropdown-menu dropdown-menu-end'
            ],
            'attributes' => [
                'class' => 'nav-item dropdown'
            ],
            'linkAttributes' => [
                'class' => 'nav-link dropdown-toggle',
                'id' => 'action-dropdown',
                'role' => 'button',
                'data-bs-toggle' => 'dropdown',
                'aria-expanded' => 'false'
            ]
        ]);


        $action->addChild('Profile', [
            'route' => 'app_security_logout',
            'linkAttributes' => ['class' => 'dropdown-item']
        ]);

        $action->addChild('divider', [
            'attributes' => [
                'class' => 'dropdown-divider'
            ],
        ]);

        $action->addChild('Logout', [
            'route' => 'app_security_logout',
            'linkAttributes' => ['class' => 'dropdown-item']
        ]);

        return $menu;
    }
}