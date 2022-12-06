<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicy;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\ShippingRate;

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
            if ($shippingRate->isEnabled() && $this->isWeightAllowed($weight, $shippingRate)) {
                return true;
            }
        }

        return false;
    }

    private function isWeightAllowed($weight, ShippingRate $shippingRate)
    {
        if ($shippingRate->getMinWeight() !== null && $shippingRate->getMaxWeight() !== null) {
            return $weight >= $shippingRate->getMinWeight() && $weight < $shippingRate->getMaxWeight();
        }

        return true;
    }
}
