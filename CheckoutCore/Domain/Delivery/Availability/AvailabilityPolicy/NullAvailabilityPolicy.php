<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;

class NullAvailabilityPolicy extends AvailabilityPolicy
{
    /**
     * @return bool
     */
    public function isAvailable()
    {
        return true;
    }
}
