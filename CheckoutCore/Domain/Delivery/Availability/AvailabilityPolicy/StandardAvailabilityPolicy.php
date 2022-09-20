<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;

use DateTime;
use DateTimeZone;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;

class StandardAvailabilityPolicy extends AvailabilityPolicy
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function isAvailable()
    {
        $time = time();
        $orderPlacementDayName = date("l", $time);
        $orderPlacementDays = $this->deliveryMethod->getProcessingDays();
        $orderPlacementDay = $orderPlacementDays[strtolower($orderPlacementDayName)];

        if ($orderPlacementDay === null) {
            // Merchant has not configured a cut-off time for this day
            return true;
        }

        if (!$orderPlacementDay->isEnabled()) {
            return false;
        }
        
        $orderPlacementDate = new DateTime('@' . $time);
        $timezone = new DateTimeZone($this->deliveryMethod->getTimeZoneName());
        $orderPlacementDate->setTimezone($timezone);

        $cutOffDate = clone $orderPlacementDate;
        date_time_set($cutOffDate, $orderPlacementDay->getCutOffHour(), $orderPlacementDay->getCutOffMinute());

        return !($cutOffDate < $orderPlacementDate);
    }
}