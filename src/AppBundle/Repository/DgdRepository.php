<?php

namespace AppBundle\Repository;

/**
 * DgdRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class DgdRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     * isAlreadyExist
     *
     * @return type
     */
    public function isAlreadyExist($contrevenants, $numero, $infraction, $valeurCaf, $dcDe, $situation, $marchandises)
    {
        $dql = " SELECT dg
            FROM AppBundle:Dgd dg
            WHERE dg.contrevenants = (?1)
            AND dg.numero = (?2)
            AND dg.infraction = (?3)
            AND dg.valeurCaf = (?4)
            AND dg.dcDe = (?5)
            AND dg.situation = (?6)
            AND dg.marchandises = (?7)
        ";
        $query = $this->_em->createQuery($dql)
                ->setParameter(1, $contrevenants)
                ->setParameter(2, $numero)
                ->setParameter(3, $infraction)
                ->setParameter(4, $valeurCaf)
                ->setParameter(5, $dcDe)
                ->setParameter(6, $situation)
                ->setParameter(7, $marchandises);
        $result = $query->getResult();
        if(count($result)>0){
            return true;
        }
        else {
            return false;
        }
        
    }

    /**
     * exportDouane
     *
     * @return type
     */
    public function exportDouane($contrevenant, $numero, $infraction, $caf, $dcde, $situation, $marchandises)
    {
        if (is_null($contrevenant) && is_null($numero) && is_null($infraction) && is_null($caf) && is_null($dcde) && is_null($situation) && is_null($marchandises)){
            return -1;
        }
        else{
            $dql = " SELECT dg
            FROM AppBundle:Dgd dg
                    ";
            $query = $this->_em->createQuery($dql);
            $result = $query->getResult();
            return $result;
        }
    }
}
