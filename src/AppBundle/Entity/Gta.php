<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Gta
 *
 * @ORM\Table(name="gta")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\GtaRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Gta
{
    use TimestampableTrait;
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
     * @Assert\NotBlank(message="Veuillez renseigner le numéro du plaque")
     * @ORM\Column(name="num_plaque", type="string", length=25)
     */
    private $numPlaque;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="daty", type="datetime")
     */
    private $daty;

    /**
     * @var string
     *
     * @ORM\Column(name="lera", type="string", length=5, nullable=true)
     */
    private $lera;

    /**
     * @var string
     *
     * @ORM\Column(name="infractions", type="text", nullable=true)
     */
    private $infractions;

    /**
     * @var bool
     *
     * @ORM\Column(name="suspect", type="boolean")
     */
    private $suspect;

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
     * Set numPlaque
     *
     * @param string $numPlaque
     *
     * @return Gta
     */
    public function setNumPlaque($numPlaque)
    {
        $this->numPlaque = $numPlaque;

        return $this;
    }

    /**
     * Get numPlaque
     *
     * @return string
     */
    public function getNumPlaque()
    {
        return $this->numPlaque;
    }

    /**
     * Set daty
     *
     * @param \DateTime $daty
     *
     * @return Gta
     */
    public function setDaty($daty)
    {
        $this->daty = $daty;

        return $this;
    }

    /**
     * Get daty
     *
     * @return \DateTime
     */
    public function getDaty()
    {
        return $this->daty;
    }

    /**
     * Set lera
     *
     * @param string $lera
     *
     * @return Gta
     */
    public function setLera($lera)
    {
        $this->lera = $lera;

        return $this;
    }

    /**
     * Get lera
     *
     * @return string
     */
    public function getLera()
    {
        return $this->lera;
    }

    /**
     * Set infractions
     *
     * @param string $infractions
     *
     * @return Gta
     */
    public function setInfractions($infractions)
    {
        $this->infractions = $infractions;

        return $this;
    }

    /**
     * Get infractions
     *
     * @return string
     */
    public function getInfractions()
    {
        return $this->infractions;
    }

    /**
     * Set suspect
     *
     * @param boolean $suspect
     *
     * @return Gta
     */
    public function setSuspect($suspect)
    {
        $this->suspect = $suspect;

        return $this;
    }

    /**
     * Get suspect
     *
     * @return boolean
     */
    public function getSuspect()
    {
        return $this->suspect;
    }
}
