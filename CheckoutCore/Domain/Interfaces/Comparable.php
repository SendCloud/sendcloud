<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Interfaces;

/**
 * Interface Comparable
 *
 * @package SendCloud\SendCloud\CheckoutCore\Domain\Contracts
 */
interface Comparable
{
    /**
     * Compares current instance to a target.
     *
     * @param object $target
     * @return boolean
     */
    public function isEqual($target);
}
