<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Velirano
 *
 * @ORM\Table(name="velirano")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VeliranoRepository")
 */
class Velirano
{
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
     * @ORM\Column(name="promise", type="text")
     */
    private $promise;

    /**
     * @var bool
     *
     * @ORM\Column(name="done", type="boolean")
     */
    private $done;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable = true)
     */
    private $comment;

    /**
     * @var int
     *
     * @ORM\Column(name="percent", type="integer")
     */
    private $percent;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    public function __construct()
    {
        $this->done = false;
        $this->comment = "";
        $this->percent = 0;
        $this->status = 'NOT_STARTED';
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
     * Set promise
     *
     * @param string $promise
     *
     * @return Velirano
     */
    public function setPromise($promise)
    {
        $this->promise = $promise;

        return $this;
    }

    /**
     * Get promise
     *
     * @return string
     */
    public function getPromise()
    {
        return $this->promise;
    }

    /**
     * Set done
     *
     * @param boolean $done
     *
     * @return Velirano
     */
    public function setDone($done)
    {
        $this->done = $done;

        return $this;
    }

    /**
     * Get done
     *
     * @return bool
     */
    public function getDone()
    {
        return $this->done;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Velirano
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Set percent
     *
     * @param integer $percent
     *
     * @return Velirano
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return int
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Velirano
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}

