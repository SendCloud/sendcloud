<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Interfaces;

interface Identifiable
{
    /**
     * Provides entity id.
     *
     * @return string | int
     */
    public function getId();
}