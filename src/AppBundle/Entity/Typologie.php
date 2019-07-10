<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Typologie
 *
 * @ORM\Table(name="typologie")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TypologieRepository")
 */
class Typologie
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
     * @ORM\Column(name="sujet", type="string", length=255)
     */
    private $sujet;

    /**
     * @ORM\ManyToOne(targetEntity="Entite", inversedBy="typologies")
     * @ORM\JoinColumn(name="entite_id", referencedColumnName="id")
     */
    private $entite;


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
     * Set sujet
     *
     * @param string $sujet
     *
     * @return Typologie
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;

        return $this;
    }

    /**
     * Get sujet
     *
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * Set entite
     *
     * @param \AppBundle\Entity\Entite $entite
     *
     * @return Typologie
     */
    public function setEntite(\AppBundle\Entity\Entite $entite = null)
    {
        $this->entite = $entite;

        return $this;
    }

    /**
     * Get entite
     *
     * @return \AppBundle\Entity\Entite
     */
    public function getEntite()
    {
        return $this->entite;
    }
}
