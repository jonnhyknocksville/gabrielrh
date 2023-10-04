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

        // SELECT DISTINCT(Month(begin_at)) from mission where user_id = 1 and Year(begin_at) = 2023;
        $query = $entityManager->createQuery(
            "SELECT DISTINCT(Month(m.beginAt))
            FROM App\Entity\Mission m
            WHERE year(m.endAt) = $year
            AND year(m.beginAt) = $year
            AND m.user = $id"
        );

        return $query->getResult();

    }

    public function findMonthlyInvoicesToGenerate($year, $month) {

        $entityManager = $this->getEntityManager();

        // SELECT DISTINCT(Month(begin_at)) from mission where user_id = 1 and Year(begin_at) = 2023;
        $query = $entityManager->createQuery(
            "SELECT DISTINCT c.id, c.name, c.city
            FROM App\Entity\Mission m
            INNER JOIN App\Entity\Clients c WITH c.id = m.client
            WHERE year(m.endAt) = $year
            AND year(m.beginAt) = $year
            AND month(m.beginAt) = $month
            AND month(m.beginAt) = $month"
        );

        return $query->getResult();

    }

    public function findCaForCurrentYear($year, $userId) {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT m
            FROM App\Entity\Mission m
            WHERE year(m.endAt) = $year
            AND year(m.beginAt) = $year
            AND m.user = $userId"
        );

        return $query->getResult();
    }

    public function findMissionForCustomer($year, $month, $clientId) {

        return $this->createQueryBuilder('m')
           ->andWhere('YEAR(m.beginAt) = :year')
           ->andWhere('YEAR(m.endAt) = :year')
           ->andWhere('MONTH(m.endAt) = :month')
           ->andWhere('MONTH(m.beginAt) = :month')
           ->andWhere('m.client = :clientId')
           ->setParameter('year', $year)
           ->setParameter('month', $month)
           ->setParameter('clientId', $clientId)
           ->getQuery()
           ->getResult();


    }


    //    public function findOneBySomeField($value): ?Mission
//    {
//        //        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

}
