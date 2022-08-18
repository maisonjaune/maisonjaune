<?php

namespace App\Service\Media\ImageManipulator\Filter;

use App\Service\Media\ImageManipulator\ImageFilterInterface;
use Symfony\Component\HttpFoundation\File\File;

class ResizerFilter implements ImageFilterInterface
{
    public static function handle(File $file, ?array $options = null): File
    {
        // TOTO manipulation de l'image avec les options
        return $file;
    }

    public static function getName(): string
    {
        return self::class;
    }
}