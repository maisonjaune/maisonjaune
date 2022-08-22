<?php

namespace App\Service\Media\ImageManipulator;

use App\Service\Media\Configurator\ImageFilterConfigurator;
use App\Service\Media\Configurator\MediaConfigurator;
use Intervention\Image\Filters\FilterInterface;
use Intervention\Image\ImageManager as InterventionImageManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class ImageManipulator extends InterventionImageManager implements ImageManipulatorInterface
{
    private Filesystem $filesystem;

    public function __construct(
        private MediaConfigurator       $mediaConfigurator,
        private ImageFilterConfigurator $imageFilterConfigurator,
    )
    {
        $this->filesystem = new Filesystem();
    }

    public function get(File $file, string $filterName): File
    {
        $configuration = $this->imageFilterConfigurator->getConfiguration($filterName);

        $image = $this->make($file->getPathname());

        $filter = $this->getFilter($configuration);

        $image->filter($filter);

        $path = $this->getDirectory($configuration) . $file->getBasename();

        $image->save($path)->destroy();

        return new File($path);
    }

    private function getFilter(ImageFilterConfiguration $configuration): FilterInterface
    {
        $service = $configuration->getService();
        return new $service($configuration->getArguments());
    }

    private function getDirectory(ImageFilterConfiguration $configuration)
    {
        $destination = implode(DIRECTORY_SEPARATOR, [
            rtrim($this->mediaConfigurator->getFullDirectory(), DIRECTORY_SEPARATOR),
            ltrim($configuration->getDirectory(), DIRECTORY_SEPARATOR),
        ]) . DIRECTORY_SEPARATOR;

        if (!$this->filesystem->exists($destination)) {
            $this->filesystem->mkdir($destination);
        }

        return $destination;
    }
}