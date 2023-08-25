<?php

namespace App\Repository;

use App\Entity\StaffApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<StaffApplication>
 *
 * @method StaffApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffApplication[]    findAll()
 * @method StaffApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffApplication::class);
    }

//    /**
//     * @return StaffApplication[] Returns an array of StaffApplication objects
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

//    public function findOneBySomeField($value): ?StaffApplication
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
