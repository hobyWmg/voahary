<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Entite
 *
 * @ORM\Table(name="entite")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EntiteRepository")
 */
class Entite
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
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="abreviation", type="string", length=255)
     */
    private $abreviation;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="entite")
     */
    private $users;

     /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="entiteReceiver", cascade={"remove"})
     */
    private $message;

    /**
     * @ORM\OneToMany(targetEntity="Typologie", mappedBy="entite")
     */
    private $typologies;


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
     * Set nom
     *
     * @param string $nom
     *
     * @return Entite
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
     * Set abreviation
     *
     * @param string $abreviation
     *
     * @return Entite
     */
    public function setAbreviation($abreviation)
    {
        $this->abreviation = $abreviation;

        return $this;
    }

    /**
     * Get abreviation
     *
     * @return string
     */
    public function getAbreviation()
    {
        return $this->abreviation;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Entite
     */
    public function addUser(\AppBundle\Entity\User $user)
    {
        $this->users[] = $user;
        $user->setEntite($this);
        return $this;
    }

    /**
     * Remove user
     *
     * @param \AppBundle\Entity\User $user
     */
    public function removeUser(\AppBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Add message
     *
     * @param \AppBundle\Entity\Message $message
     *
     * @return Entite
     */
    public function addMessage(\AppBundle\Entity\Message $message)
    {
        $this->message[] = $message;

        return $this;
    }

    /**
     * Remove message
     *
     * @param \AppBundle\Entity\Message $message
     */
    public function removeMessage(\AppBundle\Entity\Message $message)
    {
        $this->message->removeElement($message);
    }

    /**
     * Get message
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Add typology
     *
     * @param \AppBundle\Entity\Typologie $typology
     *
     * @return Entite
     */
    public function addTypology(\AppBundle\Entity\Typologie $typology)
    {
        $typology->setEntite($this);
        $this->typologies[] = $typology;

        return $this;
    }

    /**
     * Remove typology
     *
     * @param \AppBundle\Entity\Typologie $typology
     */
    public function removeTypology(\AppBundle\Entity\Typologie $typology)
    {
        $this->typologies->removeElement($typology);
    }

    /**
     * Get typologies
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTypologies()
    {
        return $this->typologies;
    }
}
