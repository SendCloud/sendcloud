<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy\CompositeAvailabilityPolicy;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy\NullAvailabilityPolicy;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy\SameDayAvailabilityPolicy;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy\StandardAvailabilityPolicy;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy\WeightAvailabilityPolicy;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;

class AvailabilityPolicyFactory
{
    /**
     * Creates instance of the AvailabilityPolicy based on the DeliveryMethod instance type
     *
     * @param DeliveryMethod $deliveryMethod
     * @param Order $order
     *
     * @return AvailabilityPolicy
     */
    public static function create(DeliveryMethod $deliveryMethod, Order $order)
    {
        $concretePolicy = static::getConcretePolicy($deliveryMethod, $order);

        if ($deliveryMethod->getShippingRateData()->isEnabled()) {
            $weightPolicy = new WeightAvailabilityPolicy($deliveryMethod, $order);

            return new CompositeAvailabilityPolicy($deliveryMethod, $order, [$concretePolicy, $weightPolicy]);
        }

        return $concretePolicy;
    }

    /**
     * @param DeliveryMethod $deliveryMethod
     * @param Order $order
     *
     * @return NullAvailabilityPolicy|SameDayAvailabilityPolicy|StandardAvailabilityPolicy
     */
    private static function getConcretePolicy(DeliveryMethod $deliveryMethod, Order $order)
    {
        if ($deliveryMethod->getType() === 'same_day_delivery') {
            return new SameDayAvailabilityPolicy($deliveryMethod, $order);
        }

        if ($deliveryMethod->getType() === 'standard_delivery') {
            return new StandardAvailabilityPolicy($deliveryMethod, $order);
        }

        return new NullAvailabilityPolicy($deliveryMethod, $order);
    }
}
