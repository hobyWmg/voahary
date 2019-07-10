<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use AppBundle\Services\ImageResizer;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=40, nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="AppBundle\Doctrine\ORM\Id\Sha1IdGenerator")
     */
    protected $id;

	/**
	 * @ORM\Column(name="firstname", type="string", length=100, nullable=true)
	 * @Assert\Length(
	 *     min=2,
	 *     minMessage="Le prénom devra comporter 2 caractères minimum",
	 * )
	 */

    private $firstname;

    /**
     * @ORM\Column(name="matricule", type="string", length=100, nullable=true)
     * @Assert\Length(
     *     min=7,
     *     max=7,
     *     minMessage="Le matricule devra comporter 7 caractères minimum",
     *     maxMessage="Le matricule devra comporter 7 caractères maximum",
     * )
     */
    private $matricule;

    /**
     * @ORM\Column(name="lastname", type="string", length=50, nullable=true)
     * @Assert\Length(
     *     min=2,
     *     minMessage="Le nom devra comporter 2 caractères minimum",
     * )
     */
    private $lastname;

     /**
     * @Assert\Length(min=4, minMessage="Le mot de passe devrait comporter au minimum 4 caractères")
     * @var string
     */
    protected $plainPassword;

    /**
     * @ORM\ManyToOne(targetEntity="Entite", inversedBy="users")
     * @ORM\JoinColumn(name="entite_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $entite;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="userSender")
     */
    private $message;

    /**
     * @ORM\OneToMany(targetEntity="ActivityLog", mappedBy="user")
     */
    private $activityLog;

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
    const THUMB_PATH = 'uploads/images/photoProfil';

    private $temp;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
            $this->$field = ImageResizer::resizeImage($image, $this->getUploadRootDir(), 840, 420, $this->$field);
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
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

     /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $email = is_null($email) ? '' : $email;
        parent::setEmail($email);
        // $this->setUsername($email);

        return $this;
    }

    /**
     * Set entite
     *
     * @param \AppBundle\Entity\Entite $entite
     *
     * @return User
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

    /**
     * Add message
     *
     * @param \AppBundle\Entity\Message $message
     *
     * @return User
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
     * Set matricule
     *
     * @param string $matricule
     *
     * @return User
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * Add activityLog
     *
     * @param \AppBundle\Entity\ActivityLog $activityLog
     *
     * @return User
     */
    public function addActivityLog(\AppBundle\Entity\ActivityLog $activityLog)
    {
        $this->activityLog[] = $activityLog;

        return $this;
    }

    /**
     * Remove activityLog
     *
     * @param \AppBundle\Entity\ActivityLog $activityLog
     */
    public function removeActivityLog(\AppBundle\Entity\ActivityLog $activityLog)
    {
        $this->activityLog->removeElement($activityLog);
    }

    /**
     * Get activityLog
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActivityLog()
    {
        return $this->activityLog;
    }
}
