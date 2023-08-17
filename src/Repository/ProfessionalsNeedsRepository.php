<?php

namespace App\Repository;

use App\Entity\ProfessionalsNeeds;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProfessionalsNeeds>
 *
 * @method ProfessionalsNeeds|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProfessionalsNeeds|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProfessionalsNeeds[]    findAll()
 * @method ProfessionalsNeeds[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfessionalsNeedsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProfessionalsNeeds::class);
    }

//    /**
//     * @return ProfessionalsNeeds[] Returns an array of ProfessionalsNeeds objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProfessionalsNeeds
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
