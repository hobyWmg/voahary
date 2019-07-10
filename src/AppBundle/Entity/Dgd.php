<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Dgd
 *
 * @ORM\Table(name="dgd")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DgdRepository")
 * @ORM\HasLifecycleCallbacks
 *
 */
class Dgd
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
     * @Assert\NotBlank(message="Veuillez renseigner le contrevenant")
     * @ORM\Column(name="contrevenants", type="string", length=255)
     */
    private $contrevenants;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le numéro")
     * @ORM\Column(name="numero", type="string", length=255)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="infraction", type="text", nullable=true)
     */
    private $infraction;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur_caf", type="string", length=255, nullable=true)
     */
    private $valeurCaf;

    /**
     * @var string
     *
     * @ORM\Column(name="dc_de", type="text", nullable=true)
     */
    private $dcDe;

    /**
     * @var string
     *
     * @ORM\Column(name="situation", type="text", nullable=true)
     */
    private $situation;

    /**
     * @var string
     *
     * @ORM\Column(name="marchandises", type="text", nullable=true)
     */
    private $marchandises;


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
     * Set contrevenants
     *
     * @param string $contrevenants
     *
     * @return Dgd
     */
    public function setContrevenants($contrevenants)
    {
        $this->contrevenants = $contrevenants;

        return $this;
    }

    /**
     * Get contrevenants
     *
     * @return string
     */
    public function getContrevenants()
    {
        return $this->contrevenants;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Dgd
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero
     *
     * @return string
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set infraction
     *
     * @param string $infraction
     *
     * @return Dgd
     */
    public function setInfraction($infraction)
    {
        $this->infraction = $infraction;

        return $this;
    }

    /**
     * Get infraction
     *
     * @return string
     */
    public function getInfraction()
    {
        return $this->infraction;
    }

    /**
     * Set valeurCaf
     *
     * @param string $valeurCaf
     *
     * @return Dgd
     */
    public function setValeurCaf($valeurCaf)
    {
        $this->valeurCaf = $valeurCaf;

        return $this;
    }

    /**
     * Get valeurCaf
     *
     * @return string
     */
    public function getValeurCaf()
    {
        return $this->valeurCaf;
    }

    /**
     * Set dcDe
     *
     * @param string $dcDe
     *
     * @return Dgd
     */
    public function setDcDe($dcDe)
    {
        $this->dcDe = $dcDe;

        return $this;
    }

    /**
     * Get dcDe
     *
     * @return string
     */
    public function getDcDe()
    {
        return $this->dcDe;
    }

    /**
     * Set situation
     *
     * @param string $situation
     *
     * @return Dgd
     */
    public function setSituation($situation)
    {
        $this->situation = $situation;

        return $this;
    }

    /**
     * Get situation
     *
     * @return string
     */
    public function getSituation()
    {
        return $this->situation;
    }

    /**
     * Set marchandises
     *
     * @param string $marchandises
     *
     * @return Dgd
     */
    public function setMarchandises($marchandises)
    {
        $this->marchandises = $marchandises;

        return $this;
    }

    /**
     * Get marchandises
     *
     * @return string
     */
    public function getMarchandises()
    {
        return $this->marchandises;
    }
}

