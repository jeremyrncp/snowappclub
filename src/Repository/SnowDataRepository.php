<?php

namespace App\Repository;

use App\Entity\Localisation;
use App\Entity\SnowData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SnowData>
 */
class SnowDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SnowData::class);
    }

    public function findBetweenDatesAndLocalisation(\DateTime $start, \DateTime $end, Localisation $localisation)
    {
        return $this->createQueryBuilder("s")
                    ->andWhere("s.date > :start")
                    ->andWhere("s.date < :end")
                    ->andWhere("s.localisation = :localisation")
                    ->setParameter("start", $start)
                    ->setParameter("end", $end)
                    ->setParameter("localisation", $localisation)
                    ->getQuery()
                    ->getResult()
            ;
    }

    //    /**
    //     * @return SnowData[] Returns an array of SnowData objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?SnowData
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
