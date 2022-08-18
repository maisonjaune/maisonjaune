<?php

namespace App\Service\Media;

use App\Entity\Media;

interface MediaPackageInterface
{
    public function getUrl(Media $media): string;
}