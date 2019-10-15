<?php

namespace AppBundle\Repository;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllEvent(){
        $qb = $this->createQueryBuilder('e')
        ->addOrderBy('e.id','DESC')
        ->setMaxResults('3');
        return $qb->getQuery()->getResult();
    }
}
