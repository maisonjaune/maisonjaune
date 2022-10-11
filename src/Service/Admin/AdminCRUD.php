<?php

namespace App\Service\Admin;

use App\Service\Admin\Route\RouterFactory;
use App\Service\Admin\Route\RouterInterface;
use App\Service\Admin\Security\Security;
use App\Service\Admin\Security\SecurityFactory;
use App\Service\Admin\Template\TemplateRegistryFactory;
use App\Service\Admin\Template\TemplateRegistryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\Request;

abstract class AdminCRUD implements AdminCRUDInterface
{
    private Security $security;

    private RouterInterface $router;

    private TemplateRegistryInterface $templateRegistry;

    private string $title;

    protected const ALIAS = 'entity';

    public function __construct(
        protected readonly EntityManagerInterface   $entityManager,
        private readonly ConfigurationListInterface $configurationList,
        private readonly PropertyRendererInterface  $propertyRenderer,
        readonly SecurityFactory                    $securityFactory,
        readonly RouterFactory                      $routerFactory,
        readonly TemplateRegistryFactory            $templateRegistryFactory,
    )
    {
        $this->title = $this->humanize($this->getRouterPrefix());
        $this->security = $securityFactory->create($this->getRouterPrefix());
        $this->router = $routerFactory->create($this->getRouterPrefix(), $this->getControllerClass());
        $this->templateRegistry = $templateRegistryFactory->create($this->getRouterPrefix(), $this->title);
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
        return $this->entityManager->find($this->getEntityClass(), $id);
    }

    protected function createQueryBuilder($indexBy = null): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()
            ->select(self::ALIAS)
            ->from($this->getEntityClass(), self::ALIAS, $indexBy);
    }

    public function getQueryFilter(Request $request, ?array $parameters = null): Query
    {
        $qb = $this->createQueryBuilder();

        if (null !== $request->get('search')) {
            $searchCondition = $qb->expr()->orX();

            foreach ($this->getConfigurationList()->getSearchableFields() as $field) {
                $searchCondition->add(sprintf('%s.%s LIKE :search', self::ALIAS, $field->getProperty()));
            }

            $qb
                ->where($searchCondition)
                ->setParameter('search', '%' . $request->get('search') . '%');
        }

        return $qb->getQuery();
    }

    public function getExtraParameters(array $parameters = []): array
    {
        return array_merge([
            'security' => $this->getSecurity(),
            'configurationList' => $this->getConfigurationList(),
            'router' => $this->getRouter(),
            'propertyRenderer' => $this->getPropertyRenderer(),
        ], $parameters);
    }

    public function getSecurity(): Security
    {
        return $this->security;
    }

    public function getConfigurationList(): ConfigurationListInterface
    {
        return $this->configurationList;
    }

    public function getRouter(): RouterInterface
    {
        return $this->router;
    }

    public function getTemplateRegistry(): TemplateRegistryInterface
    {
        return $this->templateRegistry;
    }

    public function getPropertyRenderer(): PropertyRendererInterface
    {
        return $this->propertyRenderer;
    }

    public function configurationRouter(RouterInterface $router): void
    {
    }

    public function configurationTemplateRegistry(TemplateRegistryInterface $templateRegistry): void
    {
    }

    private function humanize(string $text): string
    {
        return ucfirst(strtolower(trim(preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $text))));
    }
}