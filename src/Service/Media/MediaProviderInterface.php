<?php

namespace App\Service\Media;

use App\Entity\Media;
use Symfony\Component\HttpFoundation\File\File;

interface MediaProviderInterface
{
    public function save(Media $media): void;

    public function getFile(Media $media): File;

    public static function getName(): string;
}