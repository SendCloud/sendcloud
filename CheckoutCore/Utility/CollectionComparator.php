<?php

namespace SendCloud\SendCloud\CheckoutCore\Utility;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;

class CollectionComparator
{
    /**
     * Compares two comparable collection.
     *
     * @param Comparable[] $source
     * @param Comparable[] $target
     *
     * @return bool
     */
    public static function isEqual(array $source, array $target)
    {
        if (count($source) !== count($target)) {

            return false;
        }

        $result = true;
        foreach ($source as $index => $sourceValue) {
            if (!array_key_exists($index, $target)) {
                $result = false;
                break;
            }

            $targetValue = $target[$index];

            if ($sourceValue === null || $targetValue === null) {
                if ($sourceValue !== $targetValue) {
                    $result = false;

                    break;
                }

                continue;
            }

            if (!$sourceValue->isEqual($targetValue)) {
                $result = false;
                break;
            }
        }

        return $result;
    }
}