<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;
use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;
use SendCloud\SendCloud\CheckoutCore\Utility\CollectionComparator;

class ShippingRateData implements DTOInstantiable, Comparable
{
    /**
     * @var bool
     */
    protected $enabled;
    /**
     * @var string
     */
    protected $currency;
    /**
     * @var FreeShipping
     */
    protected $freeShipping;
    /**
     * @var ShippingRate[]
     */
    protected $shippingRates = array();

    /**
     * @param bool $enabled
     * @param string $currency
     * @param FreeShipping $freeShipping
     * @param ShippingRate[] $shippingRates
     */
    public function __construct($enabled, $currency, FreeShipping $freeShipping, array $shippingRates)
    {
        $this->enabled = $enabled;
        $this->currency = $currency;
        $this->freeShipping = $freeShipping;
        $this->shippingRates = $shippingRates;
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
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return FreeShipping
     */
    public function getFreeShipping()
    {
        return $this->freeShipping;
    }

    /**
     * @param FreeShipping $freeShipping
     */
    public function setFreeShipping($freeShipping)
    {
        $this->freeShipping = $freeShipping;
    }

    /**
     * @return ShippingRate[]
     */
    public function getShippingRates()
    {
        return $this->shippingRates;
    }

    /**
     * @param ShippingRate[] $shippingRates
     */
    public function setShippingRates($shippingRates)
    {
        $this->shippingRates = $shippingRates;
    }

    /**
     * @param ShippingRateData $target
     *
     * @return bool
     */
    public function isEqual($target)
    {
        return $this->isEnabled() === $target->isEnabled()
            && $this->getCurrency() === $target->getCurrency()
            && $this->freeShipping->isEqual($target->getFreeShipping())
            && CollectionComparator::isEqual($this->getShippingRates(), $target->getShippingRates());
    }

    /**
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\ShippingRateData $object
     *
     * @return mixed|static
     */
    public static function fromDTO($object)
    {
        $shippingRates = array();
        foreach ($object->getShippingRates() as $index => $shippingRate) {
            $shippingRates[$index] = $shippingRate !== null ? ShippingRate::fromDTO($shippingRate) : null;
        }

        return new static(
            $object->isEnabled(),
            $object->getCurrency(),
            FreeShipping::fromDTO($object->getFreeShipping()),
            $shippingRates
        );
    }


}
