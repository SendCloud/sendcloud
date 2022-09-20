<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class ShippingRateData extends DataTransferObject
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
     * @return array
     */
    public function toArray()
    {
        return array(
            'enabled' => $this->isEnabled(),
            'currency' => $this->getCurrency(),
            'free_shipping' => $this->freeShipping->toArray(),
            'shipping_rates' => ShippingRate::toArrayBatch($this->shippingRates),
        );
    }

    /**
     * @param array $rawData
     *
     * @return ShippingRateData
     */
    public static function fromArray(array $rawData)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::fromArray($rawData);
    }

    /**
     * @param array $rawData
     *
     * @return DataTransferObject|static
     */
    protected static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setEnabled(static::getValue($rawData, 'enabled', false));
        $entity->setCurrency(static::getValue($rawData, 'currency', 'EUR'));
        $entity->setFreeShipping(FreeShipping::fromArray(static::getValue($rawData, 'free_shipping', array())));
        /** @noinspection PhpParamsInspection */
        $entity->setShippingRates(ShippingRate::fromArrayBatch(static::getValue($rawData, 'shipping_rates', array())));

        return $entity;
    }
}
