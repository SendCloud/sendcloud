<?php

namespace SendCloud\SendCloud\CheckoutCore\Utility;

use SendCloud\SendCloud\CheckoutCore\Contracts\Utility\WeightUnits;
use SendCloud\SendCloud\CheckoutCore\Exceptions\Unit\UnitNotSupportedException;

class UnitConverter
{

    protected static $supportedUnits = array(WeightUnits::KILOGRAM, WeightUnits::GRAM, WeightUnits::POUNDS, WeightUnits::OUNCES);

    /**
     * Converts a weight to grams.
     *
     * @param string $unit One of the supported units: [kg, g, lbs, oz].
     * @param float $value
     *
     * @return float
     *
     * @throws UnitNotSupportedException
     */
    public static function toGrams($unit, $value)
    {
        static::validateUnit($unit);

        if ($unit === WeightUnits::KILOGRAM) {
            return $value * 1000;
        }
        if ($unit === WeightUnits::POUNDS) {
            return $value * 453.592;
        }
        if ($unit === WeightUnits::OUNCES) {
            return $value * 28.3495;
        }

        return $value;
    }

    /**
     * @param string $unit
     * @param float $value
     *
     * @return float
     * @throws UnitNotSupportedException
     */
    public static function fromGrams($unit, $value)
    {
        static::validateUnit($unit);
        if ($unit === WeightUnits::KILOGRAM) {
            return $value / 1000;
        }
        if ($unit === WeightUnits::POUNDS) {
            return $value / 453.592;
        }
        if ($unit === WeightUnits::OUNCES) {
            return $value / 28.3495;
        }

        return $value;
    }

    /**
     * @param string $unit
     *
     * @throws UnitNotSupportedException
     */
    private static function validateUnit($unit)
    {
        if (!in_array($unit, static::$supportedUnits, true)) {
            throw new UnitNotSupportedException("Weight unit $unit is not supported.");
        }
    }
}
