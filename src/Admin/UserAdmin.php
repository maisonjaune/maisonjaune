<?php

namespace App\Admin;

use App\Controller\Admin\UserController;
use App\Entity\User;
use App\Form\UserType;
use App\Service\Admin\AdminCRUD;
use App\Service\Admin\TemplateRegistry;
use App\Service\Admin\TemplateRegistryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;

class UserAdmin extends AdminCRUD
{
    public function getRouteCollection(): RouteCollection
    {
        $route = new RouteCollection();

        $route->add('admin_user_index', $this->createRoute('/user', 'index'));
        $route->add('admin_user_new', $this->createRoute('/user/new', 'new', [Request::METHOD_GET, Request::METHOD_POST]));
        $route->add('admin_user_show', $this->createRoute('/user/{id}', 'show', [Request::METHOD_GET]));
        $route->add('admin_user_edit', $this->createRoute('/user/{id}/edit', 'edit', [Request::METHOD_GET, Request::METHOD_POST]));
        $route->add('admin_user_delete', $this->createRoute('/user/{id}/delete', 'delete', [Request::METHOD_GET, Request::METHOD_POST]));

        return $route;
    }

    public function createTemplateRegistry(): TemplateRegistryInterface
    {
        $templateRegistry = new TemplateRegistry();

        $templateRegistry
            ->setTemplateIndex('admin/user/index.html.twig')
            ->setTemplateNew('admin/user/new.html.twig')
            ->setTemplateShow('admin/user/show.html.twig')
            ->setTemplateEdit('admin/user/edit.html.twig')
            ->setTemplateDelete('admin/user/delete.html.twig');

        return $templateRegistry;
    }

    public function getControllerClass(): string
    {
        return UserController::class;
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