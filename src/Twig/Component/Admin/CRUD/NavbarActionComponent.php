<?php

namespace App\Twig\Component\Admin\CRUD;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('admin-navbar-action', template: '@component/admin/CRUD/navbar-action.html.twig')]
class NavbarActionComponent
{
    public ?FormView $form;

    public ?string $url_new = null;
}