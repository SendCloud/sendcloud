<?php


namespace SendCloud\SendCloud\Checkout\Contracts;

use SendCloud\SendCloud\CheckoutCore\Configurator;

/**
 * Interface CheckouConfiguratiorFactoryInterface
 * @package SendCloud\SendCloud\Checkout\Contracts
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
