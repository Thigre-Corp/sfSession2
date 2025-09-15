<?php

namespace App\Repository;

use App\Entity\Session;
use App\Entity\Stagiaire;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Stagiaire>
 */
class StagiaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stagiaire::class);
    }

    /**
    * @return QueryBuilder Returns an QueryBuilder Objet.
    */

       public function qbAllStagiaires(): QueryBuilder
       {
           return $this->createQueryBuilder('s')
               ->orderBy('s.id', 'ASC')
           ;
       }

/*
selectionner tout les stagaiaires d'une sessions dont l'id est passé en parametre
selectionner tt lesstagires qui ne sont PAS dans le resultat précédent
-> go
https://stackoverflow.com/questions/13957330/where-not-in-query-with-doctrine-query-builder

--->
https://igm.univ-mlv.fr/~dr/XPOSE2014/Symfony/structure.html


*/














    //    /**
    //     * @return Stagiaire[] Returns an array of Stagiaire objects
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

    //    public function findOneBySomeField($value): ?Stagiaire
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
