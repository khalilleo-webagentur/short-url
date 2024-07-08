<?php

namespace App\Repository;

use App\Entity\SocialProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SocialShare>
 *
 * @method SocialShare|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialShare|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialShare[]    findAll()
 * @method SocialShare[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SocialProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SocialProfile::class);
    }

    public function save(SocialProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SocialProfile $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
