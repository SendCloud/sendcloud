<?php

namespace SendCloud\SendCloud\CheckoutCore\Utility;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Identifiable;

/**
 * Class ArrayToHashMap
 *
 * @package SendCloud\SendCloud\CheckoutCore\Utility
 */
class ArrayToHashMap
{
    /**
     * Converts array to hashmap.
     *
     * @param Identifiable[] $collection
     * @return array
     */
    public static function convert(array $collection)
    {
        $result = array();
        foreach ($collection as $item) {
            $result[$item->getId()] = $item;
        }

        return $result;
    }
}