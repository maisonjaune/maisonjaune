<?php

namespace App\EventListener;

use App\Entity\Media;

class MediaListener
{
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

    }
}