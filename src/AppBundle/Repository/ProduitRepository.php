<?php

namespace AppBundle\Repository;

/**
 * ProduitRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProduitRepository extends \Doctrine\ORM\EntityRepository
{
    public function getProduitEnAvant(){
            $qb = $this->createQueryBuilder('p')
            ->addOrderBy('p.ordre','ASC')
            ->setMaxResults('16');
            return $qb->getQuery()->getResult();
    }

    public function getAllProduct($param){
        $qb = $this->createQueryBuilder('p')
        ->addOrderBy('p.ordre','ASC');
        // ->setMaxResults('3');
        if(isset($param['search'])){
                $word = $param['search'];
                $qb->where(
                        $qb->expr()->like('p.titre', ':titre')
                )
                ->orWhere(
                        $qb->expr()->like('p.description', ':description')
                )
                ->setParameter('titre','%'.$word.'%')
                ->setParameter('description','%'.$word.'%');
        }
        return $qb->getQuery()->getResult();
    }

    public function getPreviousProduit($pId)
    {
        $qb = $this->createQueryBuilder('p')
                ->select('p')
                ->where('p.id < :pId')
                ->setParameter(':pId', $pId)
                ->orderBy('p.id', 'DESC')
                ->setFirstResult(0)
                ->setMaxResults(1)
        ;
        return  $qb->getQuery()->getOneOrNullResult();
    }

    public function getNextProduit($pId)
    {
        $qb = $this->createQueryBuilder('p')
                ->select('p')
                ->where('p.id > :pId')
                ->setParameter(':pId', $pId)
                ->orderBy('p.id', 'ASC')
                ->setFirstResult(0)
                ->setMaxResults(1)
        ;
        return  $qb->getQuery()->getOneOrNullResult();
    }
    public function getOtherProduct($p){
        $qb = $this->createQueryBuilder('p')
        ->where('p.id !=:id')
        ->setMaxResults('4')
        ->setParameter('id',$p->getId());
        return $qb->getQuery()->getResult();
    }
}
