<?php

namespace App\Repository;

use App\Entity\ResetLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResetLink|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResetLink|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResetLink[]    findAll()
 * @method ResetLink[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResetLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResetLink::class);
    }

    // /**
    //  * @return ResetLink[] Returns an array of ResetLink objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResetLink
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
