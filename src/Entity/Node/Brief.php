<?php

namespace App\Entity\Node;

use App\Entity\Node;
use App\Repository\Node\BriefRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BriefRepository::class)]
#[ORM\Table(name: "node_brief")]
class Brief extends Node
{
    public function __construct()
    {
        $this->isCommentable = false;
    }
}
