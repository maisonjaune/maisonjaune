<?php

namespace App\Twig\Component\Admin\CRUD;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('admin-navbar-action', template: '@component/admin/CRUD/navbar-action.html.twig')]
class NavbarActionComponent
{
    public string $url_new;
}