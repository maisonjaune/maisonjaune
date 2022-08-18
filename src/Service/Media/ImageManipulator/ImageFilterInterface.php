<?php

namespace App\Service\Media\ImageManipulator;

use Symfony\Component\HttpFoundation\File\File;

interface ImageFilterInterface
{
    public static function handle(File $file, ?array $options = null): File;

    public static function getName(): string;
}