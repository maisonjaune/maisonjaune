<?php

namespace App\Service\Provider\Post;

use Doctrine\Common\Collections\Collection;

interface PostProviderInterface
{
    public function findMain(): Collection;
}