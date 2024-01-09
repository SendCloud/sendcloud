<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;

/**
 * Class HandoverDay
 *
 * @package SendCloud\SendCloud\CheckoutCore\Domain\Delivery
 */
class HandoverDay implements Comparable, DTOInstantiable
{
    /**
     * @var bool
     */
    protected $enabled;
    /**
     * @var int
     */
    protected $cutOffHour;
    /**
     * @var int
     */
    protected $cutOffMinute;

    /**
     * HandoverDay constructor.
     *
     * @param bool $enabled
     * @param int $cutOffHour
     * @param int $cutOffMinute
     */
    public function __construct($enabled, $cutOffHour, $cutOffMinute)
    {
        $this->enabled = $enabled;
        $this->cutOffHour = $cutOffHour;
        $this->cutOffMinute = $cutOffMinute;
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
     * @return int
     */
    public function getCutOffHour()
    {
        return $this->cutOffHour;
    }

    /**
     * @param int $cutOffHour
     */
    public function setCutOffHour($cutOffHour)
    {
        $this->cutOffHour = $cutOffHour;
    }

    /**
     * @return int
     */
    public function getCutOffMinute()
    {
        return $this->cutOffMinute;
    }

    /**
     * @param int $cutOffMinute
     */
    public function setCutOffMinute($cutOffMinute)
    {
        $this->cutOffMinute = $cutOffMinute;
    }

    /**
     * Compares current instance to a target.
     *
     * @param HandoverDay $target
     * @return boolean
     */
    public function isEqual($target)
    {
        return $this->isEnabled() === $target->isEnabled()
            && $this->getCutOffHour() === $target->getCutOffHour()
            && $this->getCutOffMinute() === $target->getCutOffMinute();
    }

    /**
     * Makes an instance from dto.
     *
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\HandoverDay $object
     *
     * @return HandoverDay
     */
    public static function fromDTO($object)
    {
        return new static($object->isEnabled(), $object->getCutOffTimeHours(), $object->getCutOffTimeMinutes());
    }
}
