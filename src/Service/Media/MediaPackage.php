<?php

namespace App\Service\Media;

use App\Entity\Media;
use App\Service\Media\Configurator\MediaConfigurator;

class MediaPackage implements MediaPackageInterface
{
    public function __construct(private MediaConfigurator $mediaConfigurator)
    {
    }

    public function getUrl(Media $media): string
    {
        return '/' . rtrim($this->mediaConfigurator->getPublicDirectory(), '/') . '/' . $media->getPath();
    }

}