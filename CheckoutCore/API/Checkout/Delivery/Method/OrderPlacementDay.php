<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class OrderPlacementDay extends DataTransferObject
{
    /**
     * @var bool
     */
    protected $enabled;
    /**
     * @var int
     */
    protected $cutOffTimeHours;
    /**
     * @var int
     */
    protected $cutOffTimeMinutes;

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
     * @return int
     */
    public function getCutOffTimeHours()
    {
        return $this->cutOffTimeHours;
    }

    /**
     * @param int $cutOffTimeHours
     */
    public function setCutOffTimeHours($cutOffTimeHours)
    {
        $this->cutOffTimeHours = $cutOffTimeHours;
    }

    /**
     * @return int
     */
    public function getCutOffTimeMinutes()
    {
        return $this->cutOffTimeMinutes;
    }

    /**
     * @param int $cutOffTimeMinutes
     */
    public function setCutOffTimeMinutes($cutOffTimeMinutes)
    {
        $this->cutOffTimeMinutes = $cutOffTimeMinutes;
    }

    /**
     * Provides array representation of a dto.
     *
     * @return array Array representation.
     */
    public function toArray()
    {
        return [
            'enabled' => $this->isEnabled(),
            'cut_off_time_hours' => $this->getCutOffTimeHours(),
            'cut_off_time_minutes' => $this->getCutOffTimeMinutes(),
        ];
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\OrderPlacementDay DTO instance.
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
     * @return \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\OrderPlacementDay
     */
    protected static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setEnabled($rawData['enabled']);
        $entity->setCutOffTimeHours($rawData['cut_off_time_hours']);
        $entity->setCutOffTimeMinutes($rawData['cut_off_time_minutes']);

        return $entity;
    }
}
