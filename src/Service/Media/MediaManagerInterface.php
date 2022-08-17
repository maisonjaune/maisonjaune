<?php

namespace App\Service\Media;

use App\Entity\Media;

interface MediaManagerInterface
{
    public function save(Media $media): void;
}