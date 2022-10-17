<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;

/**
 * Class Carrier
 *
 * @package SendCloud\SendCloud\CheckoutCore\Domain\Delivery
 */
class Carrier implements Comparable, DTOInstantiable
{
    /**
     * @var string
     */
    protected $name;
    /**
     * @var string
     */
    protected $code;
    /**
     * @var string
     */
    protected $logoUrl;

    /**
     * Carrier constructor.
     * @param string $name
     * @param string $code
     * @param string $logoUrl
     */
    public function __construct($name, $code, $logoUrl)
    {
        $this->name = $name;
        $this->code = $code;
        $this->logoUrl = $logoUrl;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getLogoUrl()
    {
        return $this->logoUrl;
    }

    /**
     * @param string $logoUrl
     */
    public function setLogoUrl($logoUrl)
    {
        $this->logoUrl = $logoUrl;
    }

    /**
     * Compares current instance to a target.
     *
     * @param Carrier $target
     *
     * @return boolean
     */
    public function isEqual($target)
    {
        return $this->getName() === $target->getName()
            && $this->getCode() === $target->getCode()
            && $this->getLogoUrl() === $target->getLogoUrl();
    }

    /**
     * Makes an instance from dto.
     *
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\Carrier $object
     *
     * @return Carrier
     */
    public static function fromDTO($object)
    {
        return new Carrier($object->getName(), $object->getCode(), $object->getLogoUrl());
    }
}
