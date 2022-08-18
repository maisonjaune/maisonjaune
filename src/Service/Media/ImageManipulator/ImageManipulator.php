<?php

namespace App\Service\Media\ImageManipulator;

use App\Service\Media\Configurator\ImageFilterConfigurator;
use App\Service\Media\Exception\UndefinedImageFilterException;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;

class ImageManipulator implements ImageManipulatorInterface
{
    public function __construct(
        private ContainerInterface      $locator,
        private ImageFilterConfigurator $imageFilterConfigurator
    )
    {
    }

    public function get(File $file, string $filterName): File
    {
        $configuration = $this->imageFilterConfigurator->getConfiguration($filterName);

        $filter = $this->getFilter($configuration->getService());

        return $filter->handle($file, $configuration->getArguments());
    }

    public function getFilter(string $filterName): ImageFilterInterface
    {
        if (!$this->locator->has($filterName)) {
            throw new UndefinedImageFilterException($filterName);
        }

        return $this->locator->get($filterName);
    }
}