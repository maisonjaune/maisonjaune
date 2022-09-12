<?php

namespace App\Service\Admin;

use App\Service\Admin\Exception\MissingFilterRepositoryException;
use App\Service\Admin\Template\Template;
use App\Service\Admin\Template\TemplateRegistry;
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
    )
    {
        $this->router = $this->createRouter();
        $this->templateRegistry = $this->createTemplateRegistry();
        $this->configurationList($this->configurationList);
        $this->configurationRouter($this->router);
        $this->configurationTemplateRegistry($this->templateRegistry);
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

    public function createTemplateRegistry(): TemplateRegistryInterface
    {
        return (new TemplateRegistry())
            ->setTemplateIndex(new Template('admin/CRUD/index.html.twig', ucfirst(strtolower($this->getRouterPrefix())) . ' List'))
            ->setTemplateNew(new Template('admin/CRUD/new.html.twig', ucfirst(strtolower($this->getRouterPrefix())) . ' New'))
            ->setTemplateShow(new Template('admin/CRUD/show.html.twig', ucfirst(strtolower($this->getRouterPrefix())) . ' Show'))
            ->setTemplateEdit(new Template('admin/CRUD/edit.html.twig', ucfirst(strtolower($this->getRouterPrefix())) . ' Edit'))
            ->setTemplateDelete(new Template('admin/CRUD/delete.html.twig', ucfirst(strtolower($this->getRouterPrefix())) . ' Delete'));
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