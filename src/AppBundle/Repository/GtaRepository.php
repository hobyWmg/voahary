<?php

namespace AppBundle\Repository;

/**
 * GtaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GtaRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * isAlreadyExist
     *
     * @return type
     */
    public function isAlreadyExist($num, $daty, $lera)
    {
        $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.numPlaque = (?1)
            AND gt.daty LIKE :daty
            AND gt.lera = (?2)
        ";
        $datetime = new \DateTime();
        $newDate = $datetime->createFromFormat('d/m/Y', $daty);
        $query = $this->_em->createQuery($dql)
                ->setParameter(1, $num)
                ->setParameter('daty',$newDate->format('Y-m-d').'%')
                ->setParameter(2, $lera);
        $result = $query->getResult();
        if(count($result)>0){
            return true;
        }
        else {
            return false;
        }
        
    }
    /**
     * exportGendarmerie
     *
     * @return type
     */
    public function exportGendarmerie($numero, $date, $heure,$infraction, $suspect, $dateDeb, $dateFin, $selInfraction, $selVehicule)
    {
        if (is_null($numero) && is_null($date) && is_null($heure) && is_null($infraction) && is_null($suspect) && is_null($dateDeb) && is_null($dateFin)){
            return -1;
        }
        elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==-1)&&($selVehicule==-1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
                    ";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==-1)&&($selVehicule==-1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.daty BETWEEN :start AND :end ";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);
            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==0)&&($selVehicule==-1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NULL";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==-1)&&($selVehicule==0)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.suspect = 0";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        }elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==1)&&($selVehicule==-1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NOT NULL";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==-1)&&($selVehicule==1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.suspect = 1";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==1)&&($selVehicule==1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NOT NULL 
            AND gt.suspect = 1";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==0)&&($selVehicule==0)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NULL 
            AND gt.suspect = 0";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==0)&&($selVehicule==1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NULL 
            AND gt.suspect = 1";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb=="")&&($dateFin=="")&&($selInfraction==1)&&($selVehicule==0)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NOT NULL 
            AND gt.suspect = 0";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==0)&&($selVehicule==-1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NULL 
            AND gt.daty BETWEEN :start AND :end";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);

            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==-1)&&($selVehicule==0)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.suspect = 0
            AND gt.daty BETWEEN :start AND :end";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);
            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        }elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==1)&&($selVehicule==-1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NOT NULL
            AND gt.daty BETWEEN :start AND :end";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);
            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==-1)&&($selVehicule==1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.suspect = 1
            AND gt.daty BETWEEN :start AND :end";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);
            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==1)&&($selVehicule==1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NOT NULL 
            AND gt.suspect = 1
            AND gt.daty BETWEEN :start AND :end";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);
            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==0)&&($selVehicule==0)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NULL 
            AND gt.suspect = 0
            AND gt.daty BETWEEN :start AND :end";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);
            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==0)&&($selVehicule==1)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NULL 
            AND gt.suspect = 1
            AND gt.daty BETWEEN :start AND :end";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);
            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        } elseif (($dateDeb!="")&&($dateFin!="")&&($selInfraction==1)&&($selVehicule==0)){
            $dql = " SELECT gt
            FROM AppBundle:Gta gt
            WHERE gt.infractions is NOT NULL 
            AND gt.suspect = 0
            AND gt.daty BETWEEN :start AND :end";
            $datetime = new \DateTime();
            $dateStart = $datetime->createFromFormat('d/m/Y', $dateDeb);
            $datetime2 = new \DateTime();
            $dateEnd = $datetime2->createFromFormat('d/m/Y', $dateFin);
            $query = $this->_em->createQuery($dql)
                ->setParameter('start',$dateStart->format('Y-m-d').'%')
                ->setParameter('end',$dateEnd->format('Y-m-d').'%');
            $result = $query->getResult();
            return $result;
        }
        else {
            return -2;
        }
    }
}
