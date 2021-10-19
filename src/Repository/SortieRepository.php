<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

     /**
      * @return Sortie[] Returns an array of Sortie objects
     */

    public function findBySearchValue($values)
    {

        $today = date("F j, Y, g:i a");

        $qb = $this->createQueryBuilder('s');

        foreach ($values as $key => $value  ) {
            if (!empty($value)) {
                if ($key == 'site') {
                    $qb->andWhere('s.lieuId = :val')
                        ->setParameter('val', $value);
                }
                if ($key == 'search') {
                    $qb->andWhere('s.nom like :val')
                        ->setParameter('val', '%' . $value . '%');
                }
                if ($key == 'dateStart') {
                    $qb->andWhere('s.dateHeureSortie >= :val')
                        ->setParameter('val', $value);
                }
                if ($key == 'dateEnd') {
                    $qb->andWhere('s.dateLimite < :val')
                        ->setParameter('val', $value);
                }
//            if ($key == 'isOrganisateur') {
//                $qb->andWhere('s.organisateur.id like :val')
//                    ->setParameter('val', $value );
//            }
//            if ($key == 'isInscrit') {
//                $qb->andWhere('s.participants = :val')
//                    ->setParameter('val','1');
//            }
//            if ($key == 'isNotInscrit') {
//                $qb->andWhere('s.nom like :val')
//                    ->setParameter('val',$value);
//            }
                if ($key == 'isPass') {
                    $qb->andWhere('s.dateHeureSortie <= :val')
                        ->setParameter('val', $today);
                }
            }
        }
//        dd( $qb->getQuery()->getSQL());
        return $qb
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
