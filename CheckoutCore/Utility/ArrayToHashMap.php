<?php

namespace SendCloud\SendCloud\CheckoutCore\Utility;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Identifiable;

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
        $result = [];
        foreach ($collection as $item) {
            $result[$item->getId()] = $item;
        }

        return $result;
    }
}
