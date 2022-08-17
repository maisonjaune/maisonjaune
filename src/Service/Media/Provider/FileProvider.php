<?php

namespace App\Service\Media\Provider;

use App\Entity\Media;
use App\Enum\Media\ProviderEnum;
use App\Service\Media\MediaProviderInterface;

class FileProvider implements MediaProviderInterface
{
    public function save(Media $media): void
    {
        // TODO: Implement upload() method.
    }

    public static function getName(): string
    {
        return ProviderEnum::FILE->value;
    }
}