<?php

namespace App\Service\Admin;

use App\Service\Admin\Exception\MissingFilterRepositoryException;
use Doctrine\ORM\EntityManagerInterface;

abstract class AdminCRUD implements AdminCRUDInterface
{
    private TemplateRegistryInterface $templateRegistry;

    private RouterInterface $router;

    private ?FilterRepositoryInterface $repository = null;

    public function __construct(
        protected readonly EntityManagerInterface   $entityManager,
        private readonly ConfigurationListInterface $configurationList,
        private readonly PropertyRendererInterface  $propertyRenderer,
    )
    {
        $this->templateRegistry = $this->createTemplateRegistry();
        $this->router = $this->createRouter();
        $this->configurationList($this->configurationList);
        $this->configurationRouter($this->router);
    }

    private function createRouter(): RouterInterface
    {
        return (new Router($this->getRouterPrefix(), $this->getControllerClass()))
            ->addRouteIndex(sprintf('/%s', $this->getRouterPrefix()))
            ->addRouteNew(sprintf('/%s/new', $this->getRouterPrefix()))
            ->addRouteShow(sprintf('/%s/{id}', $this->getRouterPrefix()))
            ->addRouteEdit(sprintf('/%s/{id}/edit', $this->getRouterPrefix()))
            ->addRouteDelete(sprintf('/%s/{id}/delete', $this->getRouterPrefix()));
    }

    public function createEntity(): object
    {
        $class = $this->getEntityClass();

        return new $class();
    }

    public function getEntity(int $id): ?object
    {
        return $this->getRepository()->find($id);
    }

    public function getRepository(): FilterRepositoryInterface
    {
        if (null === $this->repository) {
            $repository = $this->entityManager->getRepository($this->getEntityClass());

            if (!$repository instanceof FilterRepositoryInterface) {
                throw new MissingFilterRepositoryException($repository);
            }

            $this->repository = $repository;
        }

        return $this->repository;
    }

    public function getExtraParameters(array $parameters = []): array
    {
        return array_merge([
            'configurationList' => $this->configurationList,
            'router' => $this->getRouter(),
            'propertyRenderer' => $this->propertyRenderer,
        ], $parameters);
    }

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    public function getTemplateRegistry(): TemplateRegistryInterface
    {
        return $this->templateRegistry;
    }

    public function configurationRouter(RouterInterface $router): void
    {
    }
}