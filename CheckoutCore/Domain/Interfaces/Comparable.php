<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Interfaces;

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
