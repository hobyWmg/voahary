<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Model\TimestampableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;


/**
 * Message
 *
 * @ORM\Table(name="message")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MessageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Message
{
    use TimestampableTrait;
    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=40, nullable=false)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="AppBundle\Doctrine\ORM\Id\Sha1IdGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet", type="string", length=255)
     */
    private $sujet;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;


    /**
     * @var bool
     *
     * @ORM\Column(name="vue", type="boolean", nullable=true)
     */
    private $vue;

    /**
     * @var bool
     *
     * @ORM\Column(name="papier", type="boolean", nullable=true)
     */
    private $papier;

    /**
     * @var bool
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;

     /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="message")
     * @ORM\JoinColumn(name="user_sender", referencedColumnName="id")
     *
     */
    private $userSender;

    /**
     * @ORM\ManyToOne(targetEntity="Entite", inversedBy="message")
     * @ORM\JoinColumn(name="entite_receiver", referencedColumnName="id")
     */
    private $entiteReceiver;
    
    /**
     * One message has One response.
     * @ORM\OneToOne(targetEntity="Message", mappedBy="reponse")
     */
    private $parentMessage;

    /**
     * @ORM\OneToOne(targetEntity="Message", inversedBy="parentMessage")
     * @ORM\JoinColumn(name="reponse_id", referencedColumnName="id")
     */
    private $reponse;

    // /**
    //  *
    //  * @ORM\ManyToOne(targetEntity="Typologie")
    //  * @ORM\JoinColumn(name="typologie_id", referencedColumnName="id")
    //  */
    // private $typologie;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="startDate", type="datetimetz", nullable=true)
     */
    private $startDate;

     /**
     * @var \DateTime
     *
     * @ORM\Column(name="endDate", type="datetimetz", nullable=true)
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $filename;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    private $temp;
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (isset($this->path)) {
            $this->photo = $this->performFileName($file);
            // store the old name to delete after the update
            $this->temp = $this->path;
            $this->path = null;
        } else {
            $this->path = 'initial';
        }
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            // do whatever you want to generate a unique name
            $filename = sha1(uniqid(mt_rand(), true));
            $this->path = $filename.'.'.$this->getFile()->guessExtension();
            $this->filename = $this->getFile()->getClientOriginalName();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }

        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getUploadRootDir(), $this->path);

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->getUploadRootDir().'/'.$this->temp);
            // clear the temp image path
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
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
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'uploads/documents';
    }

     /**
     * Set name
     *
     * @param string $name
     *
     * @return Document
     */
    public function setFileName($name)
    {
        $this->filename = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->filename;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Document
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
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
     * Set sujet
     *
     * @param string $sujet
     *
     * @return Message
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
     * Set text
     *
     * @param string $text
     *
     * @return Message
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set vue
     *
     * @param boolean $vue
     *
     * @return Message
     */
    public function setVue($vue)
    {
        $this->vue = $vue;

        return $this;
    }

    /**
     * Get vue
     *
     * @return bool
     */
    public function getVue()
    {
        return $this->vue;
    }

    /**
     * Set userSender
     *
     * @param \AppBundle\Entity\User $userSender
     *
     * @return Message
     */
    public function setUserSender(\AppBundle\Entity\User $userSender = null)
    {
        $this->userSender = $userSender;

        return $this;
    }

    /**
     * Get userSender
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserSender()
    {
        return $this->userSender;
    }

    /**
     * Set entiteReceiver
     *
     * @param \AppBundle\Entity\Entite $entiteReceiver
     *
     * @return Message
     */
    public function setEntiteReceiver(\AppBundle\Entity\Entite $entiteReceiver = null)
    {
        $this->entiteReceiver = $entiteReceiver;

        return $this;
    }

    /**
     * Get entiteReceiver
     *
     * @return \AppBundle\Entity\Entite
     */
    public function getEntiteReceiver()
    {
        return $this->entiteReceiver;
    }

    /**
     * Set parentMessage
     *
     * @param \AppBundle\Entity\Message $parentMessage
     *
     * @return Message
     */
    public function setParentMessage(\AppBundle\Entity\Message $parentMessage = null)
    {
        $this->parentMessage = $parentMessage;

        return $this;
    }

    /**
     * Get parentMessage
     *
     * @return \AppBundle\Entity\Message
     */
    public function getParentMessage()
    {
        return $this->parentMessage;
    }

    /**
     * Set reponse
     *
     * @param \AppBundle\Entity\Message $reponse
     *
     * @return Message
     */
    public function setReponse(\AppBundle\Entity\Message $reponse = null)
    {
        $this->reponse = $reponse;
        $reponse->setParentMessage($this);

        return $this;
    }

    /**
     * Get reponse
     *
     * @return \AppBundle\Entity\Message
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * Set papier
     *
     * @param boolean $papier
     *
     * @return Message
     */
    public function setPapier($papier)
    {
        $this->papier = $papier;

        return $this;
    }

    /**
     * Get papier
     *
     * @return boolean
     */
    public function getPapier()
    {
        return $this->papier;
    }


    /**
     * Set document
     *
     * @param \AppBundle\Entity\Document $document
     *
     * @return Message
     */
    public function setDocument(\AppBundle\Entity\Document $document = null)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return \AppBundle\Entity\Document
     */
    public function getDocument()
    {
        return $this->document;
    }

    /**
     * Set status
     *
     * @param boolean $status
     *
     * @return Message
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

    // /**
    //  * Set typologie
    //  *
    //  * @param \AppBundle\Entity\Typologie $typologie
    //  *
    //  * @return Message
    //  */
    // public function setTypologie(\AppBundle\Entity\Typologie $typologie = null)
    // {
    //     $this->typologie = $typologie;

    //     return $this;
    // }

    // /**
    //  * Get typologie
    //  *
    //  * @return \AppBundle\Entity\Typologie
    //  */
    // public function getTypologie()
    // {
    //     return $this->typologie;
    // }

    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Message
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Message
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }
}
