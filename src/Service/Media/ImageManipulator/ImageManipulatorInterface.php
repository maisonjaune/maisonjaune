<?php

namespace App\Service\Media\ImageManipulator;

use Symfony\Component\HttpFoundation\File\File;

interface ImageManipulatorInterface
{
    public function get(File $file, string $filterName): File;
}