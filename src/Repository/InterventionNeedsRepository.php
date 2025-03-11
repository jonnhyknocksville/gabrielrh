<?php

namespace App\Repository;

use App\Entity\InterventionNeeds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<InterventionNeeds>
 *
 * @method InterventionNeeds|null find($id, $lockMode = null, $lockVersion = null)
 * @method InterventionNeeds|null findOneBy(array $criteria, array $orderBy = null)
 * @method InterventionNeeds[]    findAll()
 * @method InterventionNeeds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionNeedsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, InterventionNeeds::class);
    }

    public function findInvoiceByMonthAndYear(int $clientId, string $year, string $month): ?InterventionNeeds
    {
        return $this->createQueryBuilder('i')
            ->where('i.client = :clientId')
            ->andWhere('i.year = :year')
            ->andWhere('i.month = :month')
            ->setParameter('clientId', $clientId)
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return InterventionNeeds[] Returns an array of InterventionNeeds objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?InterventionNeeds
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
