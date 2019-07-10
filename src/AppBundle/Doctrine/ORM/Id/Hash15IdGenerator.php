<?php

namespace AppBundle\Doctrine\ORM\Id;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

class Hash15IdGenerator extends AbstractIdGenerator
{
    const HASH_LENGTH = 15;

    /**
     * Generate sha1
     *
     * @param EntityManager $entityManager
     * @param \Doctrine\ORM\Mapping\Entity $entity
     *
     * @return string Hash (length = 15, regexp = [A-Z0-9]{15})
     */
    public function generate(EntityManager $entityManager, $entity)
    {
        return $this->hash(get_class($entity).microtime());
    }

    /**
     * Hash
     *
     * @param string $string
     *
     * @return string
     */
    public static function hash($string)
    {
        $hash = base_convert(md5($string), 16, 26);
        $hash = substr($hash, -self::HASH_LENGTH);
        $hash = str_pad($hash, self::HASH_LENGTH, '0', STR_PAD_LEFT);
        $hash = strtoupper($hash);

        return $hash;
    }
}
