<?php

namespace App\Service\Provider\Post;

use App\Entity\Node\Post;
use App\Repository\Node\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\QueryException;

class PostProvider implements PostProviderInterface
{
    private ArrayCollection $registrer;

    public function __construct(
        private PostRepository $postRepository,
    )
    {
        $this->registrer = new ArrayCollection();
    }

    /**
     * @return Post[]
     * @throws QueryException
     */
    public function findLastSticky(): array
    {
        $posts = $this->postRepository->findLastSticky($this->getCriteria());
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