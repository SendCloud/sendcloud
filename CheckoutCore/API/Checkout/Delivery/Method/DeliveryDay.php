<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

/**
 * Class DeliveryDay
 *
 * @package SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method
 */
class DeliveryDay extends DataTransferObject
{
    /**
     * @var boolean
     */
    protected $enabled;
    /**
     * @var int | null
     */
    protected $startingHour;
    /**
     * @var int | null
     */
    protected $startingMinute;
    /**
     * @var int | null
     */
    protected $endingHour;
    /**
     * @var int | null
     */
    protected $endingMinute;

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
     * @return int|null
     */
    public function getStartingHour()
    {
        return $this->startingHour;
    }

    /**
     * @param int|null $startingHour
     */
    public function setStartingHour($startingHour)
    {
        $this->startingHour = $startingHour;
    }

    /**
     * @return int|null
     */
    public function getStartingMinute()
    {
        return $this->startingMinute;
    }

    /**
     * @param int|null $startingMinute
     */
    public function setStartingMinute($startingMinute)
    {
        $this->startingMinute = $startingMinute;
    }

    /**
     * @return int|null
     */
    public function getEndingHour()
    {
        return $this->endingHour;
    }

    /**
     * @param int|null $endingHour
     */
    public function setEndingHour($endingHour)
    {
        $this->endingHour = $endingHour;
    }

    /**
     * @return int|null
     */
    public function getEndingMinute()
    {
        return $this->endingMinute;
    }

    /**
     * @param int|null $endingMinute
     */
    public function setEndingMinute($endingMinute)
    {
        $this->endingMinute = $endingMinute;
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
            'start_time_hours' => $this->getStartingHour(),
            'start_time_minutes' => $this->getStartingMinute(),
            'end_time_hours' => $this->getEndingHour(),
            'end_time_minutes' => $this->getEndingMinute(),
        ];
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return DeliveryDay DTO instance.
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
     * @return DeliveryDay
     *
     * @noinspection PhpDocSignatureInspection
     */
    protected static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setEnabled($rawData['enabled']);
        $entity->setStartingHour($rawData['start_time_hours']);
        $entity->setStartingMinute($rawData['start_time_minutes']);
        $entity->setEndingHour($rawData['end_time_hours']);
        $entity->setEndingMinute($rawData['end_time_minutes']);

        return $entity;
    }
}
