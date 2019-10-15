<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Services\ImageResizer;
use AppBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Carousel
 *
 * @ORM\Table(name="carousel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CarouselRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Carousel
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
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var integer
     *
     * @ORM\Column(name="ordre", type="integer", nullable=true)
     * @Gedmo\SortablePosition
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Assert\File(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     mimeTypesMessage = "message.image.invalid",
     * )
     */
    private $fileImage;

    public $toDelete = array();

    /**
     * Constant for path of directory's media image
     */
    const THUMB_PATH = 'uploads/images/carousel';

    private $temp;


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
     * @return Carousel
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
     * @return Carousel
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
     * Set ordre
     *
     * @param string $ordre
     *
     * @return Carousel
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return string
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return PhotoConsultation
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @return File
     */
    public function getFileImage()
    {
        return $this->fileImage;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $fileImage
     */
    public function setFileImage(UploadedFile $fileImage = null)
    {
        if (null != $fileImage) {
            if (!is_null($this->image)) {
                array_push($this->toDelete, $this->image);
            }
            $this->image = $this->performFileName($fileImage);
            $this->fileImage = $fileImage;
        }
    }

    /**
     * @return null|string
     */
    public function getAbsolutePath()
    {
        return $this->getUploadRootDir().'/';
    }

    public function getWebPath()
    {
        return $this->getUploadDir().'/';
    }

    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        return self::THUMB_PATH;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null === $this->getImage()) {
            return;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null !== $this->getFileImage()) {
            $this->uploadFile($this->fileImage, 'image');
        }
        $this->toDelete = array();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadedFile()
    {
        $this->toDelete = [$this->getImage()];
        foreach ($this->toDelete as $f) {
            if ( $file = $this->getAbsolutePath() . $f ) {
                if (!\is_dir($file) && \file_exists($file) ) {
                    unlink($file);
                }
            }
        }
    }

    /**
     * @param File $file
     */
    private function uploadFile($file, $field)
    {
        $file->move($this->getUploadRootDir(), $file->getClientOriginalName());
        $image = $this->getUploadRootDir().'/'.$file->getClientOriginalName();

        $this->$field = ImageResizer::resizeImage($image, $this->getUploadRootDir(), 0, 0, $this->$field);

        if (isset($this->temp)) {
            unlink($this->getUploadRootDir().'/'.$this->temp);
            $this->temp = null;
        }
        unlink($image);
        $file = null;
    }

    /**
     * @param $file
     *
     * @return string
     */
    private function performFileName($file)
    {
        return sha1(uniqid(mt_rand(), true)).'.'.$file->guessExtension();
    }
}

