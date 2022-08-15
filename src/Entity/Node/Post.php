<?php

namespace App\Entity\Node;

use App\Entity\Node;
use App\Repository\Node\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ORM\Table(name: "node_post")]
class Post extends Node
{
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $excerpt = null;

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(?string $excerpt): self
    {
        $this->excerpt = $excerpt;

        return $this;
    }
}
