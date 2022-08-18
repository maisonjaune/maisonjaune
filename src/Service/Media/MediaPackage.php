<?php

namespace App\Service\Media;

use App\Entity\Media;
use App\Service\Media\Configurator\MediaConfigurator;
use App\Service\Media\ImageManipulator\ImageManipulatorInterface;

class MediaPackage implements MediaPackageInterface
{
    public function __construct(
        private MediaConfigurator         $mediaConfigurator,
        private MediaManagerInterface     $mediaManager,
        private ImageManipulatorInterface $imageManipulator
    )
    {
    }

    public function getUrl(Media $media, ?string $filter = null): string
    {
        $provider = $this->mediaManager->getProvider($media);
        $file = $provider->getFile($media);

        if (null !== $filter) {
            $file = $this->imageManipulator->get($file, $filter);
        }

        return '/' . rtrim($this->mediaConfigurator->getPublicDirectory(), '/') . '/' . $file->getFilename();
    }

}