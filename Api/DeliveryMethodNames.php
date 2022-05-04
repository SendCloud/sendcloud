<?php

namespace SendCloud\SendCloud\Api;

/**
 * Interface DeliveryMethodNames
 * @package SendCloud\SendCloud\Api
 */
interface DeliveryMethodNames
{
    const NAMES = [
        'service_point_delivery' => 'Service point',
        'nominated_day_delivery' => 'Nominated day',
        'standard_delivery' => 'Standard',
        'same_day_delivery' => 'Same day'
    ];
}
