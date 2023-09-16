<?php

namespace App\Repository;

use App\Entity\Mission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Mission>
 *
 * @method Mission|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mission|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mission[]    findAll()
 * @method Mission[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mission::class);
    }

//    /**
//     * @return Mission[] Returns an array of Mission objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('m.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Mission
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function findMonthMissions($id, $year, $month) {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT m
            FROM App\Entity\Mission m
            WHERE year(m.endAt) = $year
            AND year(m.beginAt) = $year
            AND month(m.endAt) = $month
            AND month(m.beginAt) = $month
            AND m.user = $id"

        );

        return $query->getResult();

    }

    public function findAnnualMissions($id, $year) {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT m
            FROM App\Entity\Mission m
            WHERE year(m.endAt) = $year
            AND year(m.beginAt) = $year
            AND m.user = $id"

        );

        return $query->getResult();

    }

}
