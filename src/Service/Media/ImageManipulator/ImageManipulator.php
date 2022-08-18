<?php

namespace App\Service\Media\ImageManipulator;

use App\Entity\Media;
use App\Service\Media\Exception\UndefinedImageFilterException;
use App\Service\Media\MediaManagerInterface;
use Psr\Container\ContainerInterface;

class ImageManipulator implements ImageManipulatorInterface
{
    public function __construct(
        private ContainerInterface    $locator,
        private MediaManagerInterface $mediaManager,
    )
    {
    }

    public function getPath(Media $media, ?string $filterName = null): string
    {
        $provider = $this->mediaManager->getProvider($media);
        $file = $provider->getFile($media);

        dd($file->getPathname());

        return 'https://i.picsum.photos/id/309/800/600.jpg?hmac=uBW91qg2BVnxmsdjVXLN4K5jSBIsB-53cWHtQUHz5ak';
    }

    public function getFilter(string $filterName): ImageFilterInterface
    {
        if (!$this->locator->has($filterName)) {
            throw new UndefinedImageFilterException($filterName);
        }

        return $this->locator->get($filterName);
    }
}