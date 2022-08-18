<?php

namespace App\Service\Media\Provider;

use App\Entity\Media;
use App\Enum\Media\ProviderEnum;
use App\Service\Media\Configurator\MediaConfigurator;
use App\Service\Media\MediaProviderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileProvider implements MediaProviderInterface
{
    protected Filesystem $filesystem;

    public function __construct(private MediaConfigurator $mediaConfigurator)
    {
        $this->filesystem = new Filesystem();
    }

    public function save(Media $media): void
    {
        $this->upload($media);
    }

    public function getFile(Media $media): File
    {
        return new File($this->getPath($media));
    }

    protected function getPath(Media $media): string
    {
        return implode(DIRECTORY_SEPARATOR, [
            rtrim($this->mediaConfigurator->getFullDirectory()),
            ltrim($media->getPath())
        ]);
    }

    protected function upload(Media $media): void
    {
        match (true) {
            $media->getBinaryContent() instanceof UploadedFile => $this->uploadFile($media),
            $media->getBinaryContent() instanceof File => $this->copyFile($media),
        };
    }

    protected function uploadFile(Media $media): void
    {
        // TODO Upload du fichier dans le cas d'un UploadedFile
    }

    protected function copyFile(Media $media): void
    {
        $file = $media->getBinaryContent();

        $this->createDirectoryIfNotExist();

        $filename = $this->getUniqueFilename($file);

        $this->filesystem->copy($file->getPathname(), $this->getDestinationPath($filename));

        $this->saveInformations($media, $media->getBinaryContent(), $filename);
    }

    protected function saveInformations(Media $media, File $file, string $filename): void
    {
        $media
            ->setPath($filename)
            ->setName($file->getBasename())
            ->setSize($file->getSize())
            ->setMimeType($file instanceof UploadedFile ? $file->getClientMimeType() : $file->getMimeType());
    }

    protected function createDirectoryIfNotExist(): void
    {
        if (!$this->filesystem->exists($this->mediaConfigurator->getDirectory())) {
            $this->filesystem->mkdir($this->mediaConfigurator->getDirectory());
        }
    }

    protected function getDestinationPath(string $filename): string
    {
        return implode(DIRECTORY_SEPARATOR, [$this->mediaConfigurator->getDirectory(), $filename]);
    }

    protected function getUniqueFilename(File $file): string
    {
        return $file instanceof UploadedFile
            ? uniqid() . '.' . $file->getClientOriginalExtension()
            : uniqid() . '.' . $file->getExtension();
    }

    public static function getName(): string
    {
        return ProviderEnum::FILE->value;
    }
}