<?php

namespace App\Service\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class EntityProvider implements EntityProviderInterface
{
    public const ROW_PER_PAGE = 30;

    public function __construct(private EntityRepository $entityRepository)
    {
    }

    public function getList(Request $request, ?array $parameters = null): Collection
    {
        $query = $this->entityRepository instanceof FilterRepositoryInterface
            ? $this->entityRepository->getQueryFilter($request, $parameters)
            : $this->entityRepository->createQueryBuilder('e')->getQuery();

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