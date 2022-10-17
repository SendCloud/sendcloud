<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethods;

use RuntimeException;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethod;

class DeliveryMethodFactory
{
    /**
     * Creates instance of the DeliveryMethod based on the DeliveryMethod type
     *
     * @param  string $deliveryType
     *
     * @return DeliveryMethod
     */
    public static function create($deliveryType)
    {
        switch ($deliveryType) {
            case 'same_day_delivery':
                $instance = new SameDayDelivery();
                break;
            case 'standard_delivery':
                $instance = new StandardDelivery();
                break;
            case 'nominated_day_delivery':
                $instance = new NominatedDayDelivery();
                break;
            case 'service_point_delivery':
                $instance = new ServicePointDelivery();
                break;
            default:
                throw new RuntimeException('Unknown delivery method type.');
        }

        return $instance;
    }
}
