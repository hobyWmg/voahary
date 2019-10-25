<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MpanisaVisitor
 *
 * @ORM\Table(name="mpanisa_visitor")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MpanisaVisitorRepository")
 */
class MpanisaVisitor
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="countryCode", type="string", length=255)
     */
    private $countryCode;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre", type="integer")
     */
    private $nombre;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set countryCode
     *
     * @param string $countryCode
     *
     * @return MpanisaVisitor
     */
    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    /**
     * Get countryCode
     *
     * @return string
     */
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * Set nombre
     *
     * @param integer $nombre
     *
     * @return MpanisaVisitor
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return int
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    public function incrementNbVisitor(){
        $nb = 1;
        if($this->nombre !=null){
            $this->nombre ++;
        }else{
            $this->nombre = $nb;
        }
    }
}

