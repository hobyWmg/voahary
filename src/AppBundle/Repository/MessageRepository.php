<?php

namespace AppBundle\Repository;

/**
 * MessageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAllRecivedMessage($user){
        $qb = $this->createQueryBuilder('m')
        ->join('m.entiteReceiver','e')
        ->where('e.id =:entiteUserId')
        ->orderBy('m.id','desc')
        ->setParameter('entiteUserId',$user->getEntite()->getId());
        // return $qb->getQuery()->getResult();
        return $qb->getQuery();
    }

    public function getAllSentMessage($user){
        // dump($user);die;
        $qb = $this->createQueryBuilder('m')
        ->join('m.userSender','u')
        ->where('u.id =:userId')
        ->orderBy('m.id','desc')
        ->setParameter('userId',$user->getId());
        // return $qb->getQuery()->getResult();
        return $qb->getQuery();
    }


    public function getUnreadMessage($user){
        $qb = $this->createQueryBuilder('m')
        ->join('m.entiteReceiver','e')
        ->where('e.id =:entiteUserId')
        ->andWhere('m.status =:status or m.status is null')
        ->orderBy('m.id','desc')
        ->setParameter('entiteUserId',$user->getEntite()->getId())
        ->setParameter('status',false);
        return $qb->getQuery()->getResult();
    }

    public function getReportByFilters($filters){
        $sent = $this->getAllSentMessageByFilter($filters);
        $received = $this->getAllRecivedMessageByFilter($filters);
        $result = ['sent'=>$sent,'received'=>$received];
        return $result;
    }

    public function getAllRecivedDemandeByFilter($start=1, $nbperpage=5, $filter){
        $qb = $this->createQueryBuilder('m')
        ->join('m.entiteReceiver','e')
        ->leftJoin('m.parentMessage','p')
        ->where('e.id =:entiteUserId')
        ->andWhere('p.id is NULL ')
        ->orderBy('m.id','desc')
        ->setParameter('entiteUserId',$filter['entiteUser']->getId());
        $qb->setFirstResult($start)->setMaxResults($nbperpage) ;
        if(isset($filter['entite']) && $filter['entite']!=''){
            $qb->join('m.userSender','u')
            ->join('u.entite','en')
            ->andWhere('en.id =:entiteId')
            ->setParameter('entiteId',$filter['entite']);
        }

        if(isset($filter['status']) && $filter['status']!=null){
            $status = ($filter['status']=='1')?true:false;
            if($status){
                $qb->andWhere('m.status =:status')
            ->setParameter('status',$status); 
            }else{
                $qb->andWhere('m.status is NULL');
            }
            
        }
        if((isset($filter['dateDeb']) && $filter['dateDeb']!='') && (isset($filter['dateFin']) && $filter['dateFin']!='')){
            $qb->andWhere('m.createdAt BETWEEN :start AND :end')
            ->setParameter('start',$filter['dateDeb'])
            ->setParameter('end',$filter['dateFin']);
        }
        if((isset($filter['dateDeb']) && $filter['dateDeb']!='') && (isset($filter['dateFin']) && $filter['dateFin']=='')){
            $qb->andWhere('m.createdAt =:start')
            ->setParameter('start',$filter['dateDeb']->format("d/m/Y"));
            
        }
        if(isset($filter['typologie']) && $filter['typologie']!=''){
            $qb->join('m.typologie','t')
                ->andWhere('t.id=:typoId')
                ->setParameter('typoId',$filter['typologie']);
        }
        $total = $qb->getQuery()->getResult();
        // ->setMaxResults(5)
        // return $qb->getQuery()->getResult();
        return ['total' => count($total),'result' => $total];
    }

    public function getAllSentDemandeByFilter($start=1, $nbperpage=5, $filter){
        $qb = $this->createQueryBuilder('m')
        ->join('m.userSender','u')
        ->leftJoin('m.parentMessage','p')
        ->join('u.entite','e')
        ->where('e.id =:entiteUserId')
        ->andWhere('p.id is NULL ')
        ->orderBy('m.id','desc');
        if(isset($filter['entite']) && $filter['entite']!=null){
            $qb->join('m.entiteReceiver','en')
            ->andWhere('en.id =:entiteId')
            ->setParameter('entiteId',$filter['entite']);
        }
        if(isset($filter['status']) && $filter['status']!=null){
            $status = ($filter['status']=='1')?true:false;
            if($status){
                $qb->andWhere('m.status =:status')
            ->setParameter('status',$status); 
            }else{
                $qb->andWhere('m.status is NULL');
            } 
        }
        if((isset($filter['dateDeb']) && $filter['dateDeb']!='') && (isset($filter['dateFin']) && $filter['dateFin']!='')){
            $qb->andWhere('m.createdAt BETWEEN :start AND :end')
            ->setParameter('start',$filter['dateDeb'])
            ->setParameter('end',$filter['dateFin']);
        }
        if(isset($filter['typologie']) && $filter['typologie']!=''){
            $qb->join('m.typologie','t')
                ->andWhere('t.id=:typoId')
                ->setParameter('typoId',$filter['typologie']);
        }
        $qb->setParameter('entiteUserId',$filter['entiteUser']->getId());
        // // return $qb->getQuery()->getResult();
        // return $qb->getQuery();
        $total = $qb->getQuery()->getResult();
        $qb->setFirstResult($start)->setMaxResults($nbperpage) ;
        $result = $qb->getQuery()->getResult();
        return ['total' => count($total),'result' => $result];
    }
}
