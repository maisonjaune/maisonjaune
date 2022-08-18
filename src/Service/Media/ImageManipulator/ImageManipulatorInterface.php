<?php

namespace App\Service\Media\ImageManipulator;

use App\Entity\Media;

interface ImageManipulatorInterface
{
    public function getPath(Media $media, ?string $filterName = null): string;
}