<?php

namespace App\Service\Provider\Post;

use App\Entity\Node\Post;

interface PostProviderInterface
{
    /**
     * @return Post[]
     */
    public function findLastSticky(): array;
}