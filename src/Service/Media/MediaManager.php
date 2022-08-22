<?php

namespace App\Service\Media;

use App\Entity\Media;
use App\Service\Media\Exception\UndefinedMediaProviderException;
use Psr\Container\ContainerInterface;

class MediaManager implements MediaManagerInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    public function save(Media $media): void
    {
        $provider = $this->getProvider($media);
        $provider->save($media);
    }

    public function getProvider(Media $media): MediaProviderInterface
    {
        if (!$this->locator->has($media->getProviderName())) {
            throw new UndefinedMediaProviderException($media->getProviderName());
        }

        return $this->locator->get($media->getProviderName());
    }
}