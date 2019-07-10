<?php
/**
 * Created by PhpStorm.
 * User: Hoby
 */

namespace AppBundle\Services;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\ActivityLog;


class Log
{
    protected $em;

    public function __construct(EntityManagerInterface  $entityManager)
    {
        $this->em = $entityManager;
    }

    public function addLog($log)
    {
           $actLog = new ActivityLog();
           $actLog->setUser($log['user']);
           $actLog->setAction($log['action']);
           $actLog->setDescription($log['description']); 
           $actLog->setSuccess($log['success']);
           if(isset($log['error'])){
            $actLog->setError($log['error']);
           }
           
           $this->em->persist($actLog);
           $this->em->flush();
    }
}