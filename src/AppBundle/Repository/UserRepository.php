<?php

namespace AppBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    // public function getQueryUserByFilters($search){
    //     $qb = $this->createQueryBuilder('u');
    //     return $qb->getQuery();
    // }

    public function getQueryUserByFilters($search){
        $qb = $this->createQueryBuilder('u');
        if(isset($search['entiteId'])){
            $qb->join('u.entite','e')
            ->where('e.id =:entiteId')
            ->setParameter('entiteId',$search['entiteId']);
        }
        if(isset($search['onlyMe'])){
            $qb->andWhere('u.id =:userId')
            ->setParameter('userId',$search['onlyMe']);
        }
        return $qb->getQuery()->getResult();
    }
    
}
