<?php

namespace App\Repository;

use App\Entity\SocialProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SocialProfile>
 *
 * @method SocialProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method SocialProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method SocialProfile[]    findAll()
 * @method SocialProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
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
