<?php

namespace App\Repository;

use App\Entity\Etat;
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

    public function findBySearchValue($values, $userId)
    {
        $today = new \DateTime("now");

        $qb = $this->createQueryBuilder('s')
            ->join(Etat::class, 'et', 'WITH', 'et.id = s.etat')
            ->andWhere('et.label != :statusLabel')
            ->setParameter('statusLabel', Etat::STATUS_ARCHIVE);

        foreach ($values as $key => $value  ) {
            if (!empty($value)) {
                if ($key == 'site') {
                    $qb->andWhere('s.lieuId = :val')
                        ->setParameter('val', $value);
                }
                if ($key == 'search') {
                    $qb->andWhere('s.nom like :val2')
                        ->setParameter('val2', '%' . $value . '%');
                }
                if ($key == 'dateStart') {
                    $qb->andWhere('s.dateHeureSortie >= :val3')
                        ->setParameter('val3', $value);
                }
                if ($key == 'dateEnd') {
                    $qb->andWhere('s.dateLimite < :val4')
                        ->setParameter('val4', $value);
                }
                if ($key == 'isPass') {
                    $qb->andWhere('s.dateHeureSortie <= :val5')
                        ->setParameter('val5', $today);
                }
                if ($key == 'isOrganisateur') {
                    $qb->andWhere('s.organisateur = :val6')
                        ->setParameter('val6', $userId );
                }
                //FRANCHEMNT LA JE COMPREND PAS TROP ???
                if ($key == 'isInscrit') {
                    $qb->andWhere(':val7 MEMBER OF s.participants')
                        ->setParameter('val7',$userId);
                }
                if ($key == 'isNotInscrit') {
                    $qb->andWhere(' :val8 NOT MEMBER OF s.participants')
                        ->setParameter('val8',$userId);
                }
            }
        }
        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    public function archiveOldSortie()
    {
        $today = new \DateTime("now");
        $addOneMonth = $today->modify( 'first day of next month' );

        return $this->createQueryBuilder('s')
            ->join(Etat::class, 'et', 'WITH', 'et.id = s.etat')
            ->andWhere('s.dateHeureSortie < :val')
            ->setParameter('val', $addOneMonth)
            ->andWhere('et.label != :val2')
            ->setParameter('val2', 'archive')
            ->getQuery()
            ->getResult()
        ;
    }


    public function updateStatusSortie()
    {
        $today = new \DateTime("now");

        return $this->createQueryBuilder('s')
            ->join(Etat::class, 'et', 'WITH', 'et.id = s.etat')
            ->andWhere('s.dateHeureSortie < :val')
            ->setParameter('val', $today)
            ->andWhere('et.label != :val2')
            ->setParameter('val2', 'archive')
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
