<?php

namespace AppBundle\Model;

/**
 * Interface CreatedInterface
 */
interface CreatedInterface
{
    /**
     * Set creation date
     *
     * @param \DateTime $created
     *
     * @return object
     */
    public function setCreated(\DateTime $created = null);

    /**
     * Get creation date
     *
     * @return \DateTime
     */
    public function getCreated();
}
