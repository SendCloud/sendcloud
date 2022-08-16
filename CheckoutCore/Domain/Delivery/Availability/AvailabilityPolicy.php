<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;

abstract class AvailabilityPolicy
{
    /**
     * @var DeliveryMethod
     */
    protected $deliveryMethod;

    /**
     * @var Order
     */
    protected $order;

    /**
     * AvailabilityPolicy constructor.
     *
     * @param DeliveryMethod $deliveryMethod
     * @param Order $order
     */
    public function __construct(DeliveryMethod $deliveryMethod, Order $order)
    {
        $this->deliveryMethod = $deliveryMethod;
        $this->order = $order;
    }

    /**
     * Checks if delivery method is available
     *
     * @return bool
     */
    abstract public function isAvailable();
}
