<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;

class ServicePointData implements Comparable, DTOInstantiable
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $country;

    /**
     * @param string $apiKey
     * @param string $country
     */
    public function __construct($apiKey, $country)
    {
        $this->apiKey = $apiKey;
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Compares current instance to a target.
     *
     * @param ServicePointData $target
     * @return bool
     */
    public function isEqual($target)
    {
        return $this->getApiKey() === $target->getApiKey()
            && $this->getCountry() === $target->getCountry();
    }

    /**
     * Makes an instance from dto.
     *
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\ServicePointData $object
     * @return ServicePointData
     */
    public static function fromDTO($object)
    {
        return new ServicePointData($object->getApiKey(), $object->getCountry());
    }
}