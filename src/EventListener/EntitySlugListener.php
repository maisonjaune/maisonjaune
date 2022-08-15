<?php

namespace App\EventListener;

use App\EntityInterface\EntitySlugInterface;
use App\EntityInterface\Slug\Slug;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\AsciiSlugger;

class EntitySlugListener
{
    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof EntitySlugInterface) {
            $this->handleSlug($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if ($entity instanceof EntitySlugInterface) {
            $this->handleSlug($entity);
        }
    }

    private function handleSlug(EntitySlugInterface $entity)
    {
        $reflObject = new \ReflectionObject($entity);

        foreach ($reflObject->getProperties() as $reflProperty) {
            $attributes = $reflProperty->getAttributes(Slug::class);

            foreach ($attributes as $attribute) {
                $arguments = $attribute->getArguments();

                $slug = [];

                foreach ($arguments['fields'] as $field) {
                    $reflPropertyMain = new \ReflectionProperty($entity, $field);
                    $slug[] = $reflPropertyMain->getValue($entity);
                }

                $slugger = new AsciiSlugger();

                $reflProperty->setValue($entity, $slugger->slug(implode(' ', $slug))->lower());
            }
        }
    }
}
