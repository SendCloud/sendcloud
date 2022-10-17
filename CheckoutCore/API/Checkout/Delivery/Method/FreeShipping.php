<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class FreeShipping extends DataTransferObject
{
    /**
     * @var bool
     */
    protected $enabled;
    /**
     * @var string
     */
    protected $fromAmount;

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
    public function getFromAmount()
    {
        return $this->fromAmount;
    }

    /**
     * @param string $fromAmount
     */
    public function setFromAmount($fromAmount)
    {
        $this->fromAmount = $fromAmount;
    }

    /**
     * @inheritDoc
     * @return array
     */
    public function toArray()
    {
        return [
            'enabled' => $this->isEnabled(),
            'from_amount' => $this->getFromAmount(),
        ];
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return FreeShipping DTO instance.
     */
    public static function fromArray(array $rawData)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::fromArray($rawData);
    }

    /**
     * Factory template method used to instantiate data transfer object from an array of data.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return FreeShipping
     */
    protected static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setEnabled(static::getValue($rawData, 'enabled', false));
        $entity->setFromAmount(static::getValue($rawData, 'from_amount'));

        return $entity;
    }
}
