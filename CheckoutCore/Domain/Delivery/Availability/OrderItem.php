<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability;

class OrderItem
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var Weight
     */
    protected $weight;
    /**
     * @var int
     */
    protected $quantity;

    /**
     * @param string $id
     * @param Weight $weight
     * @param int $quantity
     */
    public function __construct($id, Weight $weight, $quantity)
    {
        $this->id = $id;
        $this->weight = $weight;
        $this->quantity = $quantity;
    }


    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Weight
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }
}
