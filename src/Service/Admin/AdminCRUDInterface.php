<?php

namespace App\Service\Admin;

use App\Service\Admin\Route\RouterInterface;
use App\Service\Admin\Security\Security;
use App\Service\Admin\Template\TemplateRegistryInterface;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\Request;

interface AdminCRUDInterface
{
    public function getEntityClass(): string;

    public function configurationList(ConfigurationListInterface $configurationList): void;

    public function configurationRouter(RouterInterface $router): void;

    public function createEntity(): object;

    public function getQueryFilter(Request $request, ?array $parameters = null): Query;

    public function getEntity(int $id): ?object;

    public function getFormType(): string;

    public function getTemplateRegistry(): TemplateRegistryInterface;

    public function getExtraParameters(array $parameters = []): array;

    public function getRouter(): RouterInterface;

    public function getControllerClass(): string;

    public function getRouterPrefix(): string;

    public function getSecurity(): Security;
}