<?php

namespace SendCloud\SendCloud\Checkout\Contracts;

use SendCloud\SendCloud\CheckoutCore\CheckoutService;

/**
 * Interface CheckoutServiceFactory
 */
interface CheckoutServiceFactoryInterface
{
    /**
     * Provides checkout service.
     *
     * @return CheckoutService
     */
    public function make(): CheckoutService;
}
