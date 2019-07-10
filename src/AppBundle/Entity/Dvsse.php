<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Dvsse
 *
 * @ORM\Table(name="dvsse")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DvsseRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Dvsse
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
     * @Assert\NotBlank(message="Veuillez renseigner la pathologie")
     * @ORM\Column(name="info", type="text")
     */
    private $info;

    /**
     * @var string
     *
     * @ORM\Column(name="pays", type="string", length=100)
     */
    private $pays;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Cas", mappedBy="dvsse", cascade="persist")
     * @Assert\Valid
     */
    protected $cas;

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
     * Set info
     *
     * @param string $info
     *
     * @return Dvsse
     */
    public function setInfo($info)
    {
        $this->info = $info;

        return $this;
    }

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo()
    {
        return $this->info;
    }

    /**
     * Set pays
     *
     * @param string $pays
     *
     * @return Dvsse
     */
    public function setPays($pays)
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * Get pays
     *
     * @return string
     */
    public function getPays()
    {
        return $this->pays;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add ca
     *
     * @param \AppBundle\Entity\Cas $ca
     *
     * @return Dvsse
     */
    public function addCa(\AppBundle\Entity\Cas $ca)
    {
        $this->cas[] = $ca;

        return $this;
    }

    /**
     * Remove ca
     *
     * @param \AppBundle\Entity\Cas $ca
     */
    public function removeCa(\AppBundle\Entity\Cas $ca)
    {
        $this->cas->removeElement($ca);
    }

    /**
     * Get cas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCas()
    {
        return $this->cas;
    }
}
