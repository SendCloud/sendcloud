<?php


namespace SendCloud\SendCloud\Helper;

class WeightConverter
{
    /**
     * Converts weight in provided unit to grams
     *
     * @param float $weight
     * @param string|null $unitToConvert
     *
     * @return float
     */
    public static function convertWeightToGrams(float $weight, string $unitToConvert): float
    {
        switch ($unitToConvert) {
            case 'Pounds':
            case 'pounds':
            case 'Pound':
            case 'pound':
            case 'LBS':
            case 'lbs':
            case 'LB':
            case 'lb':
            case 'POUND':
                return $weight * 453.592;
            case 'Ounces':
            case 'ounces':
            case 'Ounce':
            case 'ounce':
            case 'OUNCE':
                return $weight * 28.3495;
            case 'Kolograms':
            case 'kilograms':
            case 'Kiloram':
            case 'kilgram':
            case 'kg':
            case 'kgs':
            case 'KILOGRAM':
                return $weight * 1000;
            case 'Milligrams':
            case 'milligrams':
            case 'Milligram':
            case 'milligram':
            case 'mg':
                return $weight * 0.001;
            case 'Tonnes':
            case 'tonnes':
            case 'Tonne':
            case 'tonne':
            case 't':
            case 'TON':
                return $weight * 1000000;
            default:
                return $weight;
        }
    }
}
