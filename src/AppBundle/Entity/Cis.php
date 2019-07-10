<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cis
 *
 * @ORM\Table(name="cis")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CisRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Cis
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
     * @ORM\Column(name="nom", type="text")
     */
    private $nom;

    /**
     * @var string
     * @ORM\Column(name="prenom", type="text")
     */
    private $prenom;

    /**
     * @var string
     * @ORM\Column(name="autre", type="text")
     */
    private $autre;

    /**
     * @var string
     *
     * @ORM\Column(name="reseaux", type="text", nullable=true)
     */
    private $reseaux;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean")
     */
    private $status;

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
     * Set reseaux
     *
     * @param string $reseaux
     *
     * @return Cis
     */
    public function setReseaux($reseaux)
    {
        $this->reseaux = $reseaux;

        return $this;
    }

    /**
     * Get reseaux
     *
     * @return string
     */
    public function getReseaux()
    {
        return $this->reseaux;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Cis
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Cis
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return Cis
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set autre
     *
     * @param string $autre
     *
     * @return Cis
     */
    public function setAutre($autre)
    {
        $this->autre = $autre;

        return $this;
    }

    /**
     * Get autre
     *
     * @return string
     */
    public function getAutre()
    {
        return $this->autre;
    }
}
