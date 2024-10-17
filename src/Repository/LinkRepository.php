<?php

namespace App\Repository;

use App\Entity\Link;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Link>
 *
 * @method Link|null find($id, $lockMode = null, $lockVersion = null)
 * @method Link|null findOneBy(array $criteria, array $orderBy = null)
 * @method Link[]    findAll()
 * @method Link[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Link::class);
    }

    public function save(Link $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Link $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Link[]
     */
    public function searchByUserAndTitle(User $user, string $title): array
    {
        $text = '%' . $title . '%';

        $qb = $this->createQueryBuilder('t1')
            ->where('t1.user = :user')
            ->setParameter('user', $user);

        return $qb->andWhere($qb->expr()->like('t1.title', ':title'))
            ->setParameter('title', $text)
            ->orderBy('t1.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Link[]
     */
    public function filterByUser($user, $groupId, $isPublic, $hasClicks, $isFave): array
    {
        $qb = $this->createQueryBuilder('t1');

        if ($groupId > 0) {
            $qb->innerJoin('t1.collection', 't2', 'WITH', 't1.user = t2.user AND t2.id = :groupId')
                ->setParameter('groupId', $groupId);
        }

        $qb->where('t1.user = :user')
            ->setParameter('user', $user);

        if ($hasClicks) {
            $qb->andWhere('t1.counter > 0');
        }

        if ($isPublic) {
            $qb->andWhere('t1.isPublic = 1');
        }

        if ($isFave) {
            $qb->andWhere('t1.isFave = 1');
        }

        return $qb
            ->orderBy('t1.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
