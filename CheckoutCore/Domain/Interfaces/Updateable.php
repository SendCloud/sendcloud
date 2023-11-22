<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Interfaces;

interface Updateable
{
    /**
     * Checks whether the instance is different enough from target to require an update.
     *
     * @param object $target
     * @return boolean
     */
    public function canBeUpdated($target);
}
