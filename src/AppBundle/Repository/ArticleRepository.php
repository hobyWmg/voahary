<?php

namespace AppBundle\Repository;

/**
 * ArticleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ArticleRepository extends \Doctrine\ORM\EntityRepository
{
    public function getOneArticle(){
        $qb = $this->createQueryBuilder('a')
        ->addOrderBy('a.id','DESC')
        ->setMaxResults('1');
        return $qb->getQuery()->getResult();
    }
    public function getOtherArticle($a){
        $qb = $this->createQueryBuilder('a')
        ->where('a.id !=:id')
        ->setMaxResults('5')
        ->setParameter('id',$a->getId());
        return $qb->getQuery()->getResult();
    }
}
