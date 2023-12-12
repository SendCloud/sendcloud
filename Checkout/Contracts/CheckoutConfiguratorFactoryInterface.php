<?php


namespace SendCloud\SendCloud\Checkout\Contracts;

use SendCloud\SendCloud\CheckoutCore\Configurator;

/**
 * Interface CheckouConfiguratiorFactoryInterface
 */
interface CheckoutConfiguratorFactoryInterface
{
    /**
     * Provides checkout service.
     *
     * @return Configurator
     */
    public function make(): Configurator;
}
