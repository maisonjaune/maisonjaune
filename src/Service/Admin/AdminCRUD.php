<?php

namespace App\Service\Admin;

use App\Service\Admin\Exception\MissingFilterRepositoryException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;

abstract class AdminCRUD implements AdminCRUDInterface
{
    private ?FilterRepositoryInterface $repository = null;

    private TemplateRegistryInterface $templateRegistry;

    private RouterInterface $router;

    public function __construct(
        protected EntityManagerInterface $entityManager,
        private ConfigurationListInterface $configurationList,
        private PropertyRendererInterface $propertyRenderer,
    )
    {
        $this->templateRegistry = $this->createTemplateRegistry();
        $this->router = $this->createRouter();
        $this->configurationList($configurationList);
    }

    public function getTemplateRegistry(): TemplateRegistryInterface
    {
        return $this->templateRegistry;
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
}