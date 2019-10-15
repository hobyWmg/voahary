<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Services\ImageResizer;
/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EventRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Event
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
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="lieu", type="text", nullable=true)
     */
    private $lieu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @Assert\File(
     *     mimeTypes={"image/jpeg", "image/png", "image/jpg"},
     *     mimeTypesMessage = "Le type de fichier est invalide",
     * )
     */
    private $filePhoto;

    public $toDelete = array();
    
    /**
     * Constant for path of directory's media image
     */
    const THUMB_PATH = 'uploads/images/event';

    private $temp;

    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return AbstractUser
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }
    
    /**
     * @return File
     */
    public function getFilePhoto()
    {
        return $this->filePhoto;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $filePhoto
     */
    public function setFilePhoto(UploadedFile $filePhoto = null)
    {
        if (null != $filePhoto) {
            if (!is_null($this->photo)) {
                array_push($this->toDelete, $this->photo);
            }
            $this->photo = $this->performFileName($filePhoto);
            $this->filePhoto = $filePhoto;
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
        if (null === $this->getPhoto()) {
            return;
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null !== $this->getFilePhoto()) {
            $this->uploadFile($this->filePhoto, 'photo');
        }
        $this->toDelete = array();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUploadedPhoto()
    {
        $this->toDelete = [$this->getPhoto()];
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
        if ($field=='photo') {
            $this->$field = ImageResizer::resizeImage($image, $this->getUploadRootDir(), 0, 0, $this->$field);
        }
            // } else if ($field=='photo2'){
        //     $this->$field = ImageResizer::resizeImage($image, $this->getUploadRootDir(), 840, 420, $this->$field);
        // } else {
        //     $this->$field = ImageResizer::resizeImage($image, $this->getUploadRootDir(), 0, 0, $this->$field);
        // }
  
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
     * @return Event
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
     * @return Event
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
     * Set lieu
     *
     * @param string $lieu
     *
     * @return Event
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Event
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}

