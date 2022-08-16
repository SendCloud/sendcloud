<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;

class WeightAvailabilityPolicy extends AvailabilityPolicy
{

    /**
     * @inheritDoc
     */
    public function isAvailable()
    {
        return $this->isRateEnabled($this->order->calculateWeight());
    }

    /**
     * @param float $weight in grams
     *
     * @return bool
     */
    protected function isRateEnabled($weight)
    {
        if (empty($weight)) {
            return true;
        }

        $shippingRates = $this->deliveryMethod->getShippingRateData()->getShippingRates();
        foreach ($shippingRates as $shippingRate) {
            if ($shippingRate->isEnabled() && $weight >= $shippingRate->getMinWeight() && $weight < $shippingRate->getMaxWeight()) {
                return true;
            }
        }

        return false;
    }
}
