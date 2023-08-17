<?php

namespace App\Repository;

use App\Entity\TeacherApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeacherApplication>
 *
 * @method TeacherApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeacherApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeacherApplication[]    findAll()
 * @method TeacherApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeacherApplication::class);
    }

//    /**
//     * @return TeacherApplication[] Returns an array of TeacherApplication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TeacherApplication
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
