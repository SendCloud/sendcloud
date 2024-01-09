<?php

namespace SendCloud\SendCloud\Checkout\Contracts;

use SendCloud\SendCloud\CheckoutCore\CheckoutService;

/**
 * Interface CheckoutServiceFactory
 * @package SendCloud\SendCloud\Block\Checkout\Contracts
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
