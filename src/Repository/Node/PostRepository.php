<?php

namespace App\Repository\Node;

use App\Entity\Node\Post;
use App\Repository\NodeRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\QueryException;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends NodeRepository
{
    /**
     * @param Criteria|null $criteria
     * @return Post[]
     * @throws QueryException
     */
    public function findLastSticky(?Criteria $criteria = null): array
    {
        $query = $this->getQueryEntityPublish('p')
            ->andWhere('p.isSticky = 1')
            ->orderBy('p.publishedAt', 'DESC')
            ->setMaxResults(2);

        if (null !== $criteria) {
            $query->addCriteria($criteria);
        }

        return $query->getQuery()->getResult();
    }

    protected function getNodeClass(): string
    {
        return Post::class;
    }
}
