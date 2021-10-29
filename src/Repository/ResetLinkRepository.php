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
}