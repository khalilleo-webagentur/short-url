<?php

namespace App\Repository;

use App\Entity\LinkStatistic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LinkStatistic>
 *
 * @method LinkStatistic|null find($id, $lockMode = null, $lockVersion = null)
 * @method LinkStatistic|null findOneBy(array $criteria, array $orderBy = null)
 * @method LinkStatistic[]    findAll()
 * @method LinkStatistic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LinkStatisticRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LinkStatistic::class);
    }
    
    public function save(LinkStatistic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LinkStatistic $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
