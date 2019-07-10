<?php

namespace AppBundle\Model;

/**
 * Class Address Trait
 */
trait AddressTrait
{
    /**
     * Address of the fuel station that give us the daily price
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $address;

    /**
     * City (town, village, place, "Lieu-dit"...) of the fuel station
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * Zip Code of the fuel station
     * @ORM\Column(type="string", length=12, nullable=false)
     */
    private $zipCode;

    /**
     * Country of the fuel station (ISO alpha-2)
     * @ORM\Column(type="string", length=2, nullable=false)
     */
    private $country;

    /**
     * @return string
     */
    public function getAddress() : ?string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getCity() : ?string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city)
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getZipCode() : ?string
    {
        return $this->zipCode;
    }

    /**
     * @param string $zipCode
     */
    public function setZipCode(string $zipCode)
    {
        $this->zipCode = $zipCode;
    }

    /**
     * @return string
     */
    public function getCountry() : ?string
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry(string $country)
    {
        $this->country = $country;
    }
}
