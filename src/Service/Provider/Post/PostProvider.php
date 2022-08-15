<?php

namespace App\Service\Provider\Post;

use App\Repository\Node\CategoryRepository;
use App\Repository\Node\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;

class PostProvider implements PostProviderInterface
{
    private ArrayCollection $registrer;

    public function __construct(
        private PostRepository $postRepository,
        private CategoryRepository $categoryRepository,
    )
    {
        $this->registrer = new ArrayCollection();
    }

    public function findMain(): Collection
    {
        $posts = $this->postRepository->findMain($this->getCriteria());
        $this->addPostToRegistrer($posts);
        return $posts;
    }

    public function addPostToRegistrer($posts)
    {
        if (null === $posts) {
            return;
        }

        if (!is_iterable($posts)) {
            $posts = [$posts];
        }

        foreach ($posts as $post) {
            $this->registrer->add($post->getId());
        }
    }

    private function getCriteria(): ?Criteria
    {
        if ($this->registrer->count() > 0) {
            $criteria = new Criteria();
            $criteria->where(Criteria::expr()->notIn('id', $this->registrer->toArray()));
            return $criteria;
        }

        return null;
    }
}