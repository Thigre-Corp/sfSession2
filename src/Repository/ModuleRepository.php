<?php

namespace App\Repository;

use App\Entity\Module;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Module>
 */
class ModuleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Module::class);
    }

    /**
    * @return QueryBuilder Returns an QueryBuilder Objet.
    */

       public function qbAllModules(): QueryBuilder
       {
           return $this->createQueryBuilder('m')
               ->orderBy('m.categorie', 'ASC')
           ;
       }
}
