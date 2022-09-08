<?php

namespace App\Admin;

use App\Controller\Admin\UserController;
use App\Entity\User;
use App\Form\UserType;
use App\Service\Admin\AdminCRUD;
use App\Service\Admin\Router;
use App\Service\Admin\RouterInterface;
use App\Service\Admin\TemplateRegistry;
use App\Service\Admin\TemplateRegistryInterface;

class UserAdmin extends AdminCRUD
{
    public function createRouter(): RouterInterface
    {
        return (new Router('user', UserController::class))
            ->addRouteIndex('/user')
            ->addRouteNew('/user/new')
            ->addRouteShow('/user/{id}')
            ->addRouteEdit('/user/{id}/edit')
            ->addRouteDelete('/user/{id}/delete');
    }

    public function createTemplateRegistry(): TemplateRegistryInterface
    {
        return (new TemplateRegistry())
            ->setTemplateIndex('admin/user/index.html.twig')
            ->setTemplateNew('admin/user/new.html.twig')
            ->setTemplateShow('admin/user/show.html.twig')
            ->setTemplateEdit('admin/user/edit.html.twig')
            ->setTemplateDelete('admin/user/delete.html.twig');
    }

    public function getFormType(): string
    {
        return UserType::class;
    }

    public function getEntityClass(): string
    {
        return User::class;
    }
}