<?php

namespace App\Service\Admin;

use Symfony\Component\Routing\RouteCollection;

interface AdminCRUDInterface
{
    public function getControllerClass(): string;

    public function getEntityClass(): string;

    public function createEntity(): object;

    public function getEntity(int $id): ?object;

    public function getFormType(): string;

    public function getRouteCollection(): RouteCollection;

    public function getRepository(): FilterRepositoryInterface;

    public function createTemplateRegistry(): TemplateRegistryInterface;

    public function getTemplateRegistry(): TemplateRegistryInterface;
}