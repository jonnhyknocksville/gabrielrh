<?php

namespace App\Repository;

use App\Entity\FwsCookie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FwsCookie>
 *
 * @method FwsCookie|null find($id, $lockMode = null, $lockVersion = null)
 * @method FwsCookie|null findOneBy(array $criteria, array $orderBy = null)
 * @method FwsCookie[]    findAll()
 * @method FwsCookie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FwsCookieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FwsCookie::class);
    }

//    /**
//     * @return FwsCookie[] Returns an array of FwsCookie objects
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

//    public function findOneBySomeField($value): ?FwsCookie
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
