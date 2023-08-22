<?php

namespace App\Repository;

use App\Entity\Advantages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Advantages>
 *
 * @method Advantages|null find($id, $lockMode = null, $lockVersion = null)
 * @method Advantages|null findOneBy(array $criteria, array $orderBy = null)
 * @method Advantages[]    findAll()
 * @method Advantages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdvantagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Advantages::class);
    }

//    /**
//     * @return Advantages[] Returns an array of Advantages objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Advantages
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
