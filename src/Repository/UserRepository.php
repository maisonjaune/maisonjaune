<?php

namespace App\Repository;

use App\Entity\User;
use App\Service\Admin\FilterRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements FilterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function persist(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(User $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getQueryFilter(Request $request, ?array $parameters = null): Query
    {
        $qb = $this->createQueryBuilder('u');

        if (null !== $request->get('search')) {
            $searchCondition = $qb->expr()->orX()
                ->add('u.firstname LIKE :search')
                ->add('u.lastname LIKE :search')
                ->add('u.email LIKE :search');

            $qb
                ->where($searchCondition)
                ->setParameter('search', '%' . $request->get('search') . '%');
        }

        return $qb->getQuery();
    }
}
