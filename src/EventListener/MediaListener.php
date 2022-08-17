<?php

namespace App\EventListener;

use App\Entity\Media;
use App\Service\Media\MediaManagerInterface;

class MediaListener
{
    public function __construct(private MediaManagerInterface $mediaManager)
    {
    }

    public function prePersist(Media $media): void
    {
        $this->uploadBinaryContent($media);
    }

    public function preUpdate(Media $media): void
    {
        $this->uploadBinaryContent($media);
    }

    public function preRemove(Media $media): void
    {
        // TODO Supprimer le fichier physique
    }

    protected function uploadBinaryContent(Media $media): void
    {
        $this->mediaManager->save($media);
    }
}