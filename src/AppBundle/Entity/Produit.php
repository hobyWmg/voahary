<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProduitRepository")
 */
class Produit
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
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @ORM\OneToOne(targetEntity="Image", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="image_en_avant_id", referencedColumnName="id")
     */
    private $imageEnAvant;

    /**
     * @ORM\OneToMany(targetEntity="Image", mappedBy="produit", cascade={"remove"})
     */
    private $otherImages;


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
     * @return Produit
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
     * Set description
     *
     * @param string $description
     *
     * @return Produit
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set imageEnAvant
     *
     * @param \AppBundle\Entity\Image $imageEnAvant
     *
     * @return Produit
     */
    public function setImageEnAvant(\AppBundle\Entity\Image $imageEnAvant = null)
    {
        $this->imageEnAvant = $imageEnAvant;

        return $this;
    }

    /**
     * Get imageEnAvant
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImageEnAvant()
    {
        return $this->imageEnAvant;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->otherImages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add otherImage
     *
     * @param \AppBundle\Entity\Image $otherImage
     *
     * @return Produit
     */
    public function addOtherImage(\AppBundle\Entity\Image $otherImage)
    {
        $this->otherImages[] = $otherImage;
        $otherImage->setProduit($this);

        return $this;
    }

    /**
     * Remove otherImage
     *
     * @param \AppBundle\Entity\Image $otherImage
     */
    public function removeOtherImage(\AppBundle\Entity\Image $otherImage)
    {
        $this->otherImages->removeElement($otherImage);
    }

    /**
     * Get otherImages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOtherImages()
    {
        return $this->otherImages;
    }
}
