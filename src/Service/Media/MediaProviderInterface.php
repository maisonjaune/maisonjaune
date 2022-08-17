<?php

namespace App\Service\Media;

use App\Entity\Media;

interface MediaProviderInterface
{
    public function save(Media $media): void;

    public static function getName(): string;
}