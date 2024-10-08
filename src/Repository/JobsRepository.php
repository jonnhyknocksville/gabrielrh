<?php

namespace App\Repository;

use App\Entity\Jobs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Jobs>
 *
 * @method Jobs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Jobs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Jobs[]    findAll()
 * @method Jobs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Jobs::class);
    }

    //    /**
//     * @return Jobs[] Returns an array of Jobs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

    public function findJobsByCourses($listCoursesId)
    {
        // return $this->createQueryBuilder('j')
        //             ->andWhere('j.course IN (:listCoursesId)')
        //             ->setParameter('listCoursesId', $listCoursesId)
        //             ->setMaxResults(10)
        //             ->getQuery()
        //             ->getArrayResult()
        //         ;

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT j
            FROM App\Entity\Jobs j
            WHERE j.course IN($listCoursesId)"
        )->setMaxResults(8);

        return $query->getArrayResult();
    }

    public function findJobsByThemeIds(string $themeIds): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT j
            FROM App\Entity\Jobs j
            WHERE j.theme IN($themeIds)"
        )->setMaxResults(8);

        return $query->getArrayResult();
    }
}
