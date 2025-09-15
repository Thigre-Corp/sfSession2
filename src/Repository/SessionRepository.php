<?php

namespace App\Repository;

use App\Entity\Session;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Session>
 */
class SessionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Session::class);
    }

    /**
    * @return Session[] Returns an array of past Session objects
    */
    public function findPastSessions(): array
    {
        $now = new \DateTimeImmutable();
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateFin < :today')
            ->setParameter('today', $now)  //, Types::DATETIME_IMMUTABLE)
            ->orderBy('s.id', 'ASC')
        //    ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
        
    }

    /**
    * @return Session[] Returns an array of active Session objects
    */
    public function findActiveSessions(): array
    {
        $now = new \DateTimeImmutable();
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateDebut <= :today')
            ->andWhere('s.dateFin >= :today')
            ->setParameter('today', $now)  //, Types::DATETIME_IMMUTABLE)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Session[] Returns an array of future Session objects
    */
    public function findFutureSessions(): array
    {
        $now = new \DateTimeImmutable();
        return $this->createQueryBuilder('s')
            ->andWhere('s.dateDebut > :today')
            ->setParameter('today', $now)  //, Types::DATETIME_IMMUTABLE)
            ->orderBy('s.id', 'ASC')
        //    ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
    * @return Stagiaire[] Returns an array of Stagiaire objects
    */
    public function learnersNotInSession(Session $session)  :array
    {

        $session_id = $session->getId();

        $em = $this->getEntityManager();
        
        $qb = $em->createQueryBuilder(); // bingo!!!!
     
        
        $qb->select('s')
        ->from('App\Entity\Stagiaire', 's')
        ->leftJoin('s.sessions', 'se')
        ->where('se.id = :id');
        
        $sub= $em->createQueryBuilder();


        $sub->select('st')
            ->from('App\Entity\Stagiaire', 'st')
            ->where($sub->expr()->notIn('st.id', $qb->getDQL()))
            // Requête paramétrée
            ->setParameter('id', $session_id)
            // Trier la liste des stagiaires sur le nom de famille
            ->orderBy('st.nom');

        //$query = $sub->getQuery();

       $query = $sub->getQuery();

        //return $query->getResult(); // retourne erreur
        return $query->getResult();
    }

    /**
    * @return Module[] Returns an array of module objects
    */
    public function modulesNotInSession($session)
    {
        $session_id = $session->getId();

        $em = $this->getEntityManager();

        $qb = $em->createQueryBuilder(); 
        $qb->select('p')
            ->from('App\Entity\Programme', 'p')
            ->where('p.session = :id');
                
        $sub= $em->createQueryBuilder();

        $sub->select('m')
            ->from('App\Entity\Module', 'm')
            ->where($sub->expr()->notIn('m.id', $qb->getDQL()))
            ->setParameter('id', $session_id)
            ->orderBy('m.nom');

        return $sub->getQuery()->getResult();
    }


    public function foundSessions(Session $session)
    {
        $nomSearch= '%'.$session->getNom().'%';
        //dd($nomSearch);

        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('s')
            ->from('App\Entity\Session', 's')
            ->where('s.nom LIKE :nom')
            ->setParameter('nom', $nomSearch)
            ->orderBy('s.id');

        //dd($qb->getQuery());
        return $qb->getQuery()->getResult();
    }
     
}
