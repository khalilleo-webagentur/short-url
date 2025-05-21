<?php

namespace App\Repository;

use App\Entity\MaliciousUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<MaliciousUrl>
 */
class MaliciousUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaliciousUrl::class);
    }

    public function save(MaliciousUrl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(MaliciousUrl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return MaliciousUrl[]
     */
    public function findAllByCounter(): array
    {
        return $this->createQueryBuilder('t1')
            ->where('t1.counter > 0')
            ->orderBy('t1.id', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return MaliciousUrl[]
     */
    public function findAllByKeyword(string $keyword): array
    {
        return $this->createQueryBuilder('t1')
            ->where('t1.domain LIKE :keyword')
            ->setParameter('keyword', '%' . $keyword . '%')
            ->orWhere('t1.url LIKE :url')
            ->setParameter('url', '%' . $keyword . '%')
            ->orderBy('t1.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
