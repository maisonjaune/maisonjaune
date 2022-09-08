<?php

namespace App\Service\Admin;

interface AdminCRUDInterface
{
    public function getEntityClass(): string;

    public function createEntity(): object;

    public function getEntity(int $id): ?object;

    public function getFormType(): string;

    public function createRouter(): RouterInterface;

    public function getRepository(): FilterRepositoryInterface;

    public function createTemplateRegistry(): TemplateRegistryInterface;

    public function getTemplateRegistry(): TemplateRegistryInterface;

    public function getExtraParameters(array $parameters = []): array;

    public function getRouter(): RouterInterface;
}