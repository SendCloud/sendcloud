<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;

class DeliveryDay implements Comparable, DTOInstantiable
{
    /**
     * @var bool
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
     * DeliveryDay constructor.
     *
     * @param boolean $enabled
     * @param int | null $startingHour
     * @param int | null $startingMinute
     * @param int | null $endingHour
     * @param int | null $endingMinute
     */
    public function __construct($enabled, $startingHour, $startingMinute, $endingHour, $endingMinute)
    {
        $this->startingHour = $startingHour;
        $this->startingMinute = $startingMinute;
        $this->endingHour = $endingHour;
        $this->endingMinute = $endingMinute;
        $this->enabled = $enabled;
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
     * Compares current instance to a target.
     *
     * @param DeliveryDay $target
     * @return boolean
     */
    public function isEqual($target)
    {
        return $this->isEnabled() === $target->isEnabled()
            && $this->getStartingMinute() === $target->getStartingMinute()
            && $this->getStartingHour() === $target->getStartingHour()
            && $this->getEndingMinute() === $target->getEndingMinute()
            && $this->getEndingHour() === $target->getEndingHour();
    }

    /**
     * Makes an instance from dto.
     *
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryDay $object
     *
     * @return DeliveryDay
     */
    public static function fromDTO($object)
    {
        return new static(
            $object->isEnabled(),
            $object->getStartingHour(),
            $object->getStartingMinute(),
            $object->getEndingHour(),
            $object->getEndingMinute()
        );
    }
}
