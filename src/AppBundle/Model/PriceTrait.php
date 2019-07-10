<?php

namespace AppBundle\Model;

/**
 * Class PriceTrait
 */
trait PriceTrait
{
    // TODO : ADD THE CURRENCY HERE WHEN REDO THE PRICEPERLITER
    /**
     *  Price per liter for Diesel
     * @ORM\Column(type="decimal", precision=6, scale=4, nullable=true)
     */
    private $diesel = 1.3000;

    /**
     *  Price per liter for Unleaded 95
     * @ORM\Column(type="decimal", precision=6, scale=4, nullable=true)
     */
    private $unleaded95 = 1.5000;

    /**
     *  Price per liter for Unleaded 98
     * @ORM\Column(type="decimal", precision=6, scale=4, nullable=true)
     */
    private $unleaded98 = 1.5000;

    /**
     * @return float|null
     */
    public function getDiesel()
    {
        return $this->diesel;
    }

    /**
     * @param float|null $diesel
     */
    public function setDiesel($diesel)
    {
        $this->diesel = $diesel;
    }

    /**
     * @return float|null
     */
    public function getUnleaded95()
    {
        return $this->unleaded95;
    }

    /**
     * @param float|null $unleaded95
     */
    public function setUnleaded95($unleaded95)
    {
        $this->unleaded95 = $unleaded95;
    }

    /**
     * @return float|null
     */
    public function getUnleaded98()
    {
        return $this->unleaded98;
    }

    /**
     * @param float|null $unleaded98
     */
    public function setUnleaded98($unleaded98)
    {
        $this->unleaded98 = $unleaded98;
    }
}
