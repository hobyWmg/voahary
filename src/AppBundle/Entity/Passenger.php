<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Passenger
 *
 * @ORM\Table(name="passenger")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PassengerRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Passenger
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
     * @Assert\NotBlank(message="Veuillez renseigner la nationalité")
     * @ORM\Column(name="nationalite", type="string", length=50)
     */
    private $nationalite;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le numéro")
     * @ORM\Column(name="numero", type="string", length=15)
     */
    private $numero;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le nom")
     * @ORM\Column(name="nom", type="string", length=50)
     */
    private $nom;

    /**
     * @var string
     * @Assert\NotBlank(message="Veuillez renseigner le prénom")
     * @ORM\Column(name="prenom", type="string", length=100)
     */
    private $prenom;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="Veuillez renseigner la date de naissance")
     * @ORM\Column(name="date_naissance", type="datetime")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=2, nullable=true)
     */
    private $sexe;

    /**
     * @var \DateTime
     * @Assert\NotBlank(message="Veuillez renseigner la date du voyage")
     * @ORM\Column(name="date_voyage", type="datetime")
     */
    private $dateVoyage;

    /**
     * @var string
     *
     * @ORM\Column(name="transport", type="string", length=50, nullable=true)
     */
    private $transport;

    /**
     * @var string
     *
     * @ORM\Column(name="sens", type="string", length=50, nullable=true)
     */
    private $sens;

    /**
     * @var string
     *
     * @ORM\Column(name="poste_frontiere", type="string", length=150, nullable=true)
     */
    private $posteFrontiere;


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
     * Set nationalite
     *
     * @param string $nationalite
     *
     * @return Passenger
     */
    public function setNationalite($nationalite)
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    /**
     * Get nationalite
     *
     * @return string
     */
    public function getNationalite()
    {
        return $this->nationalite;
    }

    /**
     * Set numero
     *
     * @param string $numero
     *
     * @return Passenger
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
     * Set nom
     *
     * @param string $nom
     *
     * @return Passenger
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
     * @return Passenger
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
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     *
     * @return Passenger
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     *
     * @return Passenger
     */
    public function setSexe($sexe)
    {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string
     */
    public function getSexe()
    {
        return $this->sexe;
    }

    /**
     * Set dateVoyage
     *
     * @param \DateTime $dateVoyage
     *
     * @return Passenger
     */
    public function setDateVoyage($dateVoyage)
    {
        $this->dateVoyage = $dateVoyage;

        return $this;
    }

    /**
     * Get dateVoyage
     *
     * @return \DateTime
     */
    public function getDateVoyage()
    {
        return $this->dateVoyage;
    }

    /**
     * Set transport
     *
     * @param string $transport
     *
     * @return Passenger
     */
    public function setTransport($transport)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return string
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set sens
     *
     * @param string $sens
     *
     * @return Passenger
     */
    public function setSens($sens)
    {
        $this->sens = $sens;

        return $this;
    }

    /**
     * Get sens
     *
     * @return string
     */
    public function getSens()
    {
        return $this->sens;
    }

    /**
     * Set posteFrontiere
     *
     * @param string $posteFrontiere
     *
     * @return Passenger
     */
    public function setPosteFrontiere($posteFrontiere)
    {
        $this->posteFrontiere = $posteFrontiere;

        return $this;
    }

    /**
     * Get posteFrontiere
     *
     * @return string
     */
    public function getPosteFrontiere()
    {
        return $this->posteFrontiere;
    }
}

