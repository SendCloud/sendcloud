<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability;

use SendCloud\SendCloud\CheckoutCore\Contracts\Utility\WeightUnits;

class Weight
{
    /**
     * @var float
     */
    protected $value;
    /**
     * @see WeightUnits
     *
     * @var string
     */
    protected $unit;

    /**
     * @param float $value
     * @param string $unit
     */
    public function __construct($value, $unit)
    {
        $this->value = $value;
        $this->unit = $unit;
    }

    /**
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }
}