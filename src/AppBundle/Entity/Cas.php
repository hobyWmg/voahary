<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cas
 *
 * @ORM\Table(name="cas")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CasRepository")
 */
class Cas
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
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\NotBlank(message="Veuillez renseigner le titre")
     */
    private $titre;

    /**
     * @var int
     *
     * @ORM\Column(name="nombre", type="bigint", nullable=true)
     */
    private $nombre;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Dvsse", inversedBy="cas")
     * @ORM\JoinColumn(name="dvsse_id", onDelete="CASCADE")
     */
    private $dvsse;

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
     * Set titre
     *
     * @param string $titre
     *
     * @return Cas
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set nombre
     *
     * @param integer $nombre
     *
     * @return Cas
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

    /**
     * Set dvsse
     *
     * @param \AppBundle\Entity\Dvsse $dvsse
     *
     * @return Cas
     */
    public function setDvsse(\AppBundle\Entity\Dvsse $dvsse = null)
    {
        $this->dvsse = $dvsse;

        return $this;
    }

    /**
     * Get dvsse
     *
     * @return \AppBundle\Entity\Dvsse
     */
    public function getDvsse()
    {
        return $this->dvsse;
    }
}
