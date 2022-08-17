<?php

namespace App\Entity;

use App\Enum\Media\ContextEnum;
use App\Enum\Media\ProviderEnum;
use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?ProviderEnum $providerName = null;

    #[ORM\Column()]
    private ?ContextEnum $context = null;

    #[ORM\Column(length: 255)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 100)]
    private ?string $mimeType = null;

    #[ORM\Column(nullable: true)]
    private ?int $size = null;

    #[ORM\Column(nullable: true)]
    private ?int $width = null;

    #[ORM\Column(nullable: true)]
    private ?int $height = null;

    private $binaryContent;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProviderName(): ?ProviderEnum
    {
        return $this->providerName;
    }

    public function setProviderName(ProviderEnum $providerName): self
    {
        $this->providerName = $providerName;

        return $this;
    }

    public function getContext(): ?ContextEnum
    {
        return $this->context;
    }

    public function setContext(ContextEnum $context): self
    {
        $this->context = $context;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(?int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(?int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getBinaryContent(): ?File
    {
        return $this->binaryContent;
    }

    public function setBinaryContent(File $binaryContent): self
    {
        $this->path = null;
        $this->binaryContent = $binaryContent;

        return $this;
    }
}
