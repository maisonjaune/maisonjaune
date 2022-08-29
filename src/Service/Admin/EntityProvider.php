<?php

namespace App\Service\Admin;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;

class EntityProvider implements EntityProviderInterface
{
    public function __construct(private EntityRepository $entityRepository)
    {
    }

    public function getList(Request $request): Collection
    {
        return new ArrayCollection($this->entityRepository->findAll());
    }
}