<?php

namespace App\Repository;

use App\Entity\FaqTeachers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FaqTeachers>
 *
 * @method FaqTeachers|null find($id, $lockMode = null, $lockVersion = null)
 * @method FaqTeachers|null findOneBy(array $criteria, array $orderBy = null)
 * @method FaqTeachers[]    findAll()
 * @method FaqTeachers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqTeachersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FaqTeachers::class);
    }

//    /**
//     * @return FaqTeachers[] Returns an array of FaqTeachers objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FaqTeachers
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
