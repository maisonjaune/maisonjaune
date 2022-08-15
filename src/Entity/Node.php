<?php

namespace App\Entity;

use App\EntityInterface\EntitySlugInterface;
use App\EntityInterface\Slug\Slug;
use App\EntityInterface\Timestamp\EntityTimestampTrait;
use App\EntityInterface\Timestamp\EntityTimestampInterface;
use App\Repository\NodeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NodeRepository::class)]
#[ORM\InheritanceType("JOINED")]
#[ORM\DiscriminatorColumn(name: "node_type", type: "string")]
abstract class Node implements EntityTimestampInterface, EntitySlugInterface
{
    use EntityTimestampTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    #[ORM\Column(length: 255)]
    protected ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Slug(fields: ["title"])]
    protected ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    protected ?string $content = null;

    #[ORM\Column(nullable: true)]
    protected ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column]
    protected ?bool $isActif = false;

    #[ORM\Column]
    protected ?bool $isDraft = false;

    #[ORM\Column]
    protected ?bool $isSticky = false;

    #[ORM\Column]
    protected ?bool $isCommentable = true;

    #[ORM\ManyToOne]
    protected ?User $author = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->isActif;
    }

    public function setIsActif(bool $isActif): self
    {
        $this->isActif = $isActif;

        return $this;
    }

    public function isDraft(): ?bool
    {
        return $this->isDraft;
    }

    public function setIsDraft(bool $isDraft): self
    {
        $this->isDraft = $isDraft;

        return $this;
    }

    public function isSticky(): ?bool
    {
        return $this->isSticky;
    }

    public function setIsSticky(bool $isSticky): self
    {
        $this->isSticky = $isSticky;

        return $this;
    }

    public function isCommentable(): ?bool
    {
        return $this->isCommentable;
    }

    public function setIsCommentable(bool $isCommentable): self
    {
        $this->isCommentable = $isCommentable;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }
}
