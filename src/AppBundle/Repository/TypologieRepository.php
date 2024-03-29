<?php

namespace AppBundle\Repository;

/**
 * TypologieRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TypologieRepository extends \Doctrine\ORM\EntityRepository
{
    public function getTypologieByEntite($eId){
        $qb = $this->createQueryBuilder('t')
        ->join('t.entite','e')
        ->where('e.id =:entityId')
        ->setParameter('entityId',$eId);
        return $qb->getQuery()->getResult();
    }
    public function findAllGroupByEntite(){
        $qb = $this->createQueryBuilder('t')
        ->join('t.entite','e');
        // ->addSelect('e.id');
        // ->groupBy('e.id');
        return $qb->getQuery()->getResult();
    }
}
