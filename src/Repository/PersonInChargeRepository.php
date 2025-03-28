<?php

namespace App\Repository;

use App\Entity\PersonInCharge;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PersonInCharge>
 */
class PersonInChargeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonInCharge::class);
    }

    //    /**
    //     * @return PersonInCharge[] Returns an array of PersonInCharge objects
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

    //    public function findOneBySomeField($value): ?PersonInCharge
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function getDistinctSchools(): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.school') // Récupère uniquement les écoles uniques
            ->where('p.school IS NOT NULL') // Évite les valeurs nulles
            ->orderBy('p.school', 'ASC') // Trie par ordre alphabétique
            ->getQuery()
            ->getSingleColumnResult(); // Retourne un tableau simple avec uniquement les noms des écoles
    }

    public function getDistinctCities(): array
    {
        return $this->createQueryBuilder('p')
            ->select('DISTINCT p.city') // Récupère uniquement les écoles uniques
            ->where('p.city IS NOT NULL') // Évite les valeurs nulles
            ->orderBy('p.city', 'ASC') // Trie par ordre alphabétique
            ->getQuery()
            ->getSingleColumnResult(); // Retourne un tableau simple avec uniquement les noms des écoles
    }

}
