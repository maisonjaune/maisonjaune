<?php

namespace App\EventListener;

use App\EntityInterface\Timestamp\EntityTimestampInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;

class EntityTimestampListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof EntityTimestampInterface) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof EntityTimestampInterface) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
