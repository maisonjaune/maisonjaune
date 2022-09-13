<?php

namespace App\Service\Admin;

use App\Service\Admin\Exception\MissingFilterRepositoryException;
use App\Service\Admin\Route\RouterFactory;
use App\Service\Admin\Route\RouterInterface;
use App\Service\Admin\Template\TemplateRegistryFactory;
use App\Service\Admin\Template\TemplateRegistryInterface;
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
        readonly RouterFactory                      $routerFactory,
        readonly TemplateRegistryFactory            $templateRegistryFactory,
    )
    {
        $this->router = $routerFactory->create($this->getRouterPrefix(), $this->getControllerClass());
        $this->templateRegistry = $templateRegistryFactory->create($this->getRouterPrefix());
        $this->configurationList($this->configurationList);
        $this->configurationRouter($this->router);
        $this->configurationTemplateRegistry($this->templateRegistry);
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

    public function configurationTemplateRegistry(TemplateRegistryInterface $templateRegistry): void
    {
    }
}