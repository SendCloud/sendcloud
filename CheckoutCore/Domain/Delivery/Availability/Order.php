<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability;

use SendCloud\SendCloud\CheckoutCore\Utility\UnitConverter;

class Order
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var OrderItem[]
     */
    protected $orderItems;

    /**
     * @param string $id
     * @param OrderItem[] $orderItems
     */
    public function __construct($id, array $orderItems)
    {
        $this->id = $id;
        $this->orderItems = $orderItems;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return OrderItem[]
     */
    public function getOrderItems()
    {
        return $this->orderItems;
    }

    /**
     * Returns total order weight in grams
     *
     * @return float
     * @throws \SendCloud\SendCloud\CheckoutCore\Exceptions\Unit\UnitNotSupportedException
     */
    public function calculateWeight()
    {
        $totalWeight = 0;
        foreach ($this->orderItems as $orderItem) {
            $itemWeight = $orderItem->getWeight();
            $totalWeight += UnitConverter::toGrams($itemWeight->getUnit(), $orderItem->getQuantity() * $itemWeight->getValue());
        }

        return $totalWeight;
    }
}
