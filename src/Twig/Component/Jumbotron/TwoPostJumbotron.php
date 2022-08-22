<?php

namespace App\Twig\Component\Jumbotron;

use App\Entity\Node\Post;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('two-post-jumbotron', template: '.components/jumbotrons/two-post-jumbotron.html.twig')]
class TwoPostJumbotron
{
    /**
     * @var Post[]
     */
    public array $posts;

    public function getMainPost(): ?Post
    {
        return $this->posts[0] ?? null;
    }

    public function getSecondaryPost(): ?Post
    {
        return $this->posts[1] ?? null;
    }
}