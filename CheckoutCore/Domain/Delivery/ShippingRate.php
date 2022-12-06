<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;
use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class ShippingRate implements Comparable, DTOInstantiable
{
    /**
     * @var string
     */
    protected $rate;
    /**
     * @var bool
     */
    protected $enabled;
    /**
     * @var bool
     */
    protected $isDefault;
    /**
     * @var int|null
     */
    protected $maxWeight;
    /**
     * @var int|null
     */
    protected $minWeight;

    /**
     * @param $rate
     * @param $enabled
     * @param $isDefault
     * @param $minWeight
     * @param $maxWeight
     */
    public function __construct($rate, $enabled, $isDefault, $minWeight, $maxWeight)
    {
        $this->rate = $rate;
        $this->enabled = $enabled;
        $this->isDefault = $isDefault;
        $this->minWeight = $minWeight;
        $this->maxWeight = $maxWeight;
    }

    /**
     * @return string
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param string $rate
     */
    public function setRate($rate)
    {
        $this->rate = $rate;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * @return bool
     */
    public function isDefault()
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     */
    public function setIsDefault($isDefault)
    {
        $this->isDefault = $isDefault;
    }

    /**
     * @return int|null
     */
    public function getMaxWeight()
    {
        return $this->maxWeight;
    }

    /**
     * @param int|null $maxWeight
     */
    public function setMaxWeight($maxWeight)
    {
        $this->maxWeight = $maxWeight;
    }

    /**
     * @return int|null
     */
    public function getMinWeight()
    {
        return $this->minWeight;
    }

    /**
     * @param int|null $minWeight
     */
    public function setMinWeight($minWeight)
    {
        $this->minWeight = $minWeight;
    }

    /**
     * @param ShippingRate $target
     *
     * @return bool
     */
    public function isEqual($target)
    {
        return $this->getRate() === $target->getRate()
            && $this->isEnabled() === $target->isEnabled()
            && $this->isDefault() === $target->isDefault()
            && $this->getMinWeight() === $target->getMinWeight()
            && $this->getMaxWeight() === $target->getMaxWeight();
    }

    /**
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\ShippingRate $object
     *
     * @return mixed|void
     */
    public static function fromDTO($object)
    {
        return new static(
            $object->getRate(),
            $object->isEnabled(),
            $object->isDefault(),
            $object->getMinWeight(),
            $object->getMaxWeight()
        );
    }
}
