<?php

namespace App\Service\Media\Provider;

use App\Entity\Media;
use App\Enum\Media\ProviderEnum;

class ImageProvider extends FileProvider
{
    public function save(Media $media): void
    {
        // TODO: Implement upload() method.
    }

    public static function getName(): string
    {
        return ProviderEnum::IMAGE->value;
    }
}