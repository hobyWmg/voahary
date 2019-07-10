<?php

namespace AppBundle\Model;

/**
 * Interface UpdatedInterface
 */
interface UpdatedInterface
{
    /**
     * Set update date
     *
     * @param \DateTime $created
     *
     * @return object
     */
    public function setUpdated(\DateTime $created = null);

    /**
     * Get update date
     *
     * @return \DateTime
     */
    public function getUpdated();
}
