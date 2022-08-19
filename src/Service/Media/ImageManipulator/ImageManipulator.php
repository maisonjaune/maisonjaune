<?php

namespace App\Service\Media\ImageManipulator;

use App\Service\Media\Configurator\ImageFilterConfigurator;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\ImageManager as InterventionImageManager;
use Symfony\Component\HttpFoundation\File\File;

class ImageManipulator extends InterventionImageManager implements ImageManipulatorInterface
{
    public function __construct(private ImageFilterConfigurator $imageFilterConfigurator)
    {
    }

    public function get(File $file, string $filterName): File
    {
        $configuration = $this->imageFilterConfigurator->getConfiguration($filterName);

        $image = $this->make($file->getPathname());

        $filter = $this->getFilter($configuration);

        $image->filter($filter);

        // TODO: Transformer l'image en File.

        return $file;
    }

    private function getFilter(ImageFilterConfiguration $configuration): FilterInterface
    {
        $service = $configuration->getService();
        return new $service($configuration->getArguments());
    }
}