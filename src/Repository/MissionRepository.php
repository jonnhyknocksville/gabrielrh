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

    public function findMonthMissions($id, $year, $month)
    {

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

    public function findAnnualMissions($id, $year)
    {

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

    // Méthode permettant de trouver tous les clients différents chez qui on intervient sur l'année en cours
    public function findDistinctClientsForCurrentYear($year)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT DISTINCT c.id, c.name, c.commercialName, c.city
            FROM App\Entity\Mission m
            INNER JOIN App\Entity\Clients c WITH c.id = m.client 
            WHERE year(m.beginAt) 
            BETWEEN $year and $year + 1"
        );

        return $query->getResult();
    }


    public function findMonthlyInvoicesToGenerate($year, $month)
    {

        $entityManager = $this->getEntityManager();

        // SELECT DISTINCT(Month(begin_at)) from mission where user_id = 1 and Year(begin_at) = 2023;
        $query = $entityManager->createQuery(
            "SELECT m
            FROM App\Entity\Mission m
            INNER JOIN App\Entity\Clients c WITH c.id = m.client
            INNER JOIN App\Entity\Students s WITH s.id = m.student
            WHERE year(m.endAt) = $year
            AND year(m.beginAt) = $year
            AND month(m.beginAt) = $month
            AND month(m.beginAt) = $month"
        );

        return $query->getResult();

    }

    /**
     * Méthode permettant de récupérer les factures que les formateurs peuvent générer chaque mois si ils n'ont pas de logiciel comptable
     */
    public function findMonthlyInvoicesToGenerateForUser($year, $month, $idUser)
    {

        $entityManager = $this->getEntityManager();

        // SELECT DISTINCT(Month(begin_at)) from mission where user_id = 1 and Year(begin_at) = 2023;
        $query = $entityManager->createQuery(
            "SELECT m
            FROM App\Entity\Mission m
            INNER JOIN App\Entity\Clients c WITH c.id = m.client
            INNER JOIN App\Entity\Students s WITH s.id = m.student
            WHERE year(m.endAt) = $year
            AND year(m.beginAt) = $year
            AND month(m.beginAt) = $month
            AND month(m.beginAt) = $month
            AND m.user = $idUser"
        );

        return $query->getResult();

    }

    public function findCaForCurrentYear($year, $userId)
    {
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

    public function findMissionForCustomer($year, $month, $clientId, $orderNumber)
    {

        if (!is_null($orderNumber)) {
            return $this->createQueryBuilder('m')
                ->andWhere('YEAR(m.beginAt) = :year')
                ->andWhere('YEAR(m.endAt) = :year')
                ->andWhere('MONTH(m.endAt) = :month')
                ->andWhere('MONTH(m.beginAt) = :month')
                ->andWhere('m.client = :clientId')
                ->andWhere('m.orderNumber = :orderNumber')
                ->setParameter('year', $year)
                ->setParameter('month', $month)
                ->setParameter('clientId', $clientId)
                ->setParameter('orderNumber', $orderNumber)
                ->getQuery()
                ->getResult();
        } else {
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
    }

    /**
     * Méthode permettant de récupérer toutes les missions pour l'une de mes boites qu'un formateur souhaite me faccture pour lui montrer un modele de facture
     */
    public function findAllMissionToGenerateInvoiceForTeacher($year, $month, $clientId, $userId)
    {

        return $this->createQueryBuilder('m')
            ->andWhere('YEAR(m.beginAt) = :year')
            ->andWhere('YEAR(m.endAt) = :year')
            ->andWhere('MONTH(m.endAt) = :month')
            ->andWhere('MONTH(m.beginAt) = :month')
            ->andWhere('m.invoice_client = :invoiceClient')
            ->andWhere('m.user = :userId')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('invoiceClient', $clientId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }


    public function findMissionForCustomerAndOneTeacher($year, $month, $clientId, $userId)
    {

        // dd("tptp");
        return $this->createQueryBuilder('m')
            ->andWhere('YEAR(m.beginAt) = :year')
            ->andWhere('YEAR(m.endAt) = :year')
            ->andWhere('MONTH(m.endAt) = :month')
            ->andWhere('MONTH(m.beginAt) = :month')
            ->andWhere('m.client = :clientId')
            ->andWhere('m.user = :userId')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('clientId', $clientId)
            ->setParameter('userId', $userId)
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

    public function updateClientPaidForMissions($year, $month, $clientId, $paid)
    {


        $query = $this->createQueryBuilder('m')->update(Mission::class, 'm')
            ->set('m.clientPaid', ':paid')
            ->where('m.client = :clientId')
            ->andWhere('MONTH(m.beginAt) = :month')
            ->andWhere('MONTH(m.endAt) = :month')
            ->andWhere('YEAR(m.beginAt) = :year')
            ->andWhere('YEAR(m.endAt) = :year')
            ->setParameter('clientId', $clientId)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('paid', $paid)
            ->getQuery()->execute();

    }

    public function updateInvoiceSentForMissions($year, $month, $clientId, $sent)
    {

        $query = $this->createQueryBuilder('m')->update(Mission::class, 'm')
            ->set('m.invoiceSent', ':sent')
            ->where('m.client = :clientId')
            ->andWhere('MONTH(m.beginAt) = :month')
            ->andWhere('MONTH(m.endAt) = :month')
            ->andWhere('YEAR(m.beginAt) = :year')
            ->andWhere('YEAR(m.endAt) = :year')
            ->setParameter('clientId', $clientId)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('sent', $sent)
            ->getQuery()->execute();

    }

    public function updateTeacherPaidForMissions($year, $month, $userId, $paid)
    {

        $query = $this->createQueryBuilder('m')->update(Mission::class, 'm')
            ->set('m.teacherPaid', ':paid')
            ->where('m.user = :userId')
            ->andWhere('MONTH(m.beginAt) = :month')
            ->andWhere('MONTH(m.endAt) = :month')
            ->andWhere('YEAR(m.beginAt) = :year')
            ->andWhere('YEAR(m.endAt) = :year')
            ->setParameter('userId', $userId)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->setParameter('paid', $paid)
            ->getQuery()->execute();

    }

    public function findDifferentCoursesForClientAndYear($clientId, $year)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT c.title, s.hourlyPrice, s.student 
            FROM App\Entity\Mission m
            INNER JOIN App\Entity\Students s WITH s.id = m.student 
            INNER JOIN App\Entity\Courses c WITH c.id = m.course 
            WHERE year(m.beginAt) 
            BETWEEN $year and ($year + 1) AND m.client = $clientId
            GROUP BY m.course, s.id"
        );

        return $query->getResult();
    }

    public function findDifferentTeachersForClientAndCourseAndYear($clientId, $year)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT c.title, s.student, SUM(m.hours * m.nbrDays) as totalHours, u.firstName, u.lastName
            FROM App\Entity\Mission m
            INNER JOIN App\Entity\Students s WITH s.id = m.student 
            INNER JOIN App\Entity\Courses c WITH c.id = m.course 
            INNER JOIN App\Entity\User u WITH u.id = m.user 
            WHERE year(m.beginAt) 
            BETWEEN $year and ($year + 1) AND m.client = $clientId
            GROUP BY m.course, s.id, u.id"
        );

        return $query->getResult();
    }

    public function findDistinctTeachersForCustomers($clientId, $year)
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT DISTINCT u.firstName, u.lastName 
            FROM App\Entity\Mission m
            INNER JOIN App\Entity\User u WITH u.id = m.user 
            WHERE year(m.beginAt) 
            BETWEEN $year and ($year + 1) AND m.client = $clientId"
        );

        return $query->getResult();
    }

    public function findDistinctThemesFromUserMissions($userId): array
    {
        $qb = $this->createQueryBuilder('m')
        ->join('m.course', 'c')
        ->join('c.theme', 't')
        ->select('DISTINCT t.id') // Sélection des champs du thème
        ->where('m.user = :userId')
        ->setParameter('userId', $userId)
        ->orderBy('t.id', 'ASC');

    return $qb->getQuery()->getResult();
    }

}
