<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;

use DateTime;
use DateTimeZone;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Holiday;

class SameDayAvailabilityPolicy extends AvailabilityPolicy
{
    /**
     * @return bool
     * @throws \Exception
     */
    public function isAvailable()
    {
	    $time = time();
        $deliveryDayName = date("l", $time);
        $carrierDeliveryDays = $this->deliveryMethod->getShippingProduct()->getDeliveryDays();
        $carrierDeliveryDay = $carrierDeliveryDays[strtolower($deliveryDayName)];

        if ($carrierDeliveryDay === null) {
            // Carrier is not delivering on this date
            return false;
        }

        $parcelHandoverDays = $this->deliveryMethod->getProcessingDays();
        $parcelHandoverDay = $parcelHandoverDays[strtolower($deliveryDayName)];
        if ($parcelHandoverDay === null || $parcelHandoverDay->isEnabled() === false) {
            // Merchant can’t hand-over the parcel to the carrier on this date
            return false;
        }

        $deliveryDate = new DateTime('@' . $time);
        $timezone = new DateTimeZone($this->deliveryMethod->getTimeZoneName());
        $deliveryDate->setTimezone($timezone);

        $cutOffDate = clone $deliveryDate;
        date_time_set($cutOffDate, $parcelHandoverDay->getCutOffHour(),
            $parcelHandoverDay->getCutOffMinute());

        if ($cutOffDate < $deliveryDate) {
            return false;
        }

        $isParcelHandoverDateHoliday = $this->isHoliday($this->deliveryMethod->getHolidays(), $deliveryDate);
        if ($isParcelHandoverDateHoliday) {
            return false;
        }

        return true;
    }

    /**
     * Checks if parcel handover date is holiday
     *
     * @param Holiday[] $holidays
     * @param $parcelHandoverDate
     *
     * @return bool
     * @throws \Exception
     */
    private function isHoliday($holidays, $parcelHandoverDate)
    {
        foreach ($holidays as $holiday) {
            $holidayFromDate = new DateTime($holiday->getFromDate());
            $holidayToDate = new DateTime($holiday->getToDate());

            if ($holiday->isRecurring() && $holiday->getFrequency() === 'yearly' && $holidayFromDate < $parcelHandoverDate) {
                $yearDifference = date_format($parcelHandoverDate, 'Y') - date_format($holidayFromDate, 'Y');
                $holidayFromDate->modify("+ {$yearDifference} years");
                $holidayToDate->modify("+ {$yearDifference} years");
            }

            if ($holidayFromDate < $parcelHandoverDate && $parcelHandoverDate < $holidayToDate) {
                return true;
            }
        }

        return false;
    }
}