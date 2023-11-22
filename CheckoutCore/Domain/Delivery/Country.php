<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Zone\Location;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;

class Country implements Comparable, DTOInstantiable
{
    /**
     * Country iso 2 code.
     *
     * @var string
     */
    protected $isoCode;
    /**
     * @var
     */
    protected $name;

    /**
     * Country constructor.
     * @param string $isoCode
     * @param $name
     */
    public function __construct($isoCode, $name)
    {
        $this->isoCode = $isoCode;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * @param string $isoCode
     */
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Compares current instance to a target.
     *
     * @param Country $target
     *
     * @return boolean
     */
    public function isEqual($target)
    {
        return $this->getIsoCode() === $target->getIsoCode()
            && $this->getName() === $target->getName();
    }

    /**
     * Makes an instance from dto.
     *
     * @param Location $object
     *
     * @return Country
     */
    public static function fromDTO($object)
    {
        return new static($object->getIsoCode(), $object->getName());
    }
}
