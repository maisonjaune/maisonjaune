<?php

namespace App\Entity\Node;

use App\Entity\Node;
use App\Repository\Node\PageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
#[ORM\Table(name: "node_page")]
class Page extends Node
{
    public function __construct()
    {
        $this->isCommentable = false;
    }
}
