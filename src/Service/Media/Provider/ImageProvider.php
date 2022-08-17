<?php

namespace App\Service\Media\Provider;

use App\Entity\Media;
use App\Enum\Media\ProviderEnum;
use Symfony\Component\HttpFoundation\File\File;

class ImageProvider extends FileProvider
{
    protected function saveInformations(Media $media, File $file, string $filename): void
    {
        parent::saveInformations($media, $file, $filename);

        $data = getimagesize($file);

        if (!!$data) {
            $media
                ->setWidth($data[0])
                ->setHeight($data[1]);
        }
    }

    public static function getName(): string
    {
        return ProviderEnum::IMAGE->value;
    }
}