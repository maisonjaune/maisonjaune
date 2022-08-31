<?php

namespace App\Service\Admin;

use App\Service\Admin\Exception\MissingFilterRepositoryException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class EntityProvider implements EntityProviderInterface
{
    private FilterRepositoryInterface $filterRepository;

    public const ROW_PER_PAGE = 30;

    public function __construct(EntityRepository $entityRepository)
    {
        if (!$entityRepository instanceof FilterRepositoryInterface) {
            throw new MissingFilterRepositoryException($entityRepository);
        }

        $this->filterRepository = $entityRepository;
    }

    public function getList(Request $request, ?array $parameters = null): Collection
    {
        $query = $this->filterRepository->getQueryFilter($request, $parameters);

        $query
            ->setFirstResult($this->getFirstResult($request))
            ->setMaxResults($this->getMaxResult());

        return new ArrayCollection($query->getResult());
    }

    private function getFirstResult(Request $request): int
    {
        return $request->get('page', 1) * self::ROW_PER_PAGE - self::ROW_PER_PAGE;
    }

    private function getMaxResult(): int
    {
        return self::ROW_PER_PAGE;
    }
}