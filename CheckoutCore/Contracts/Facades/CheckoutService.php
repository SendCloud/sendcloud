<?php

namespace SendCloud\SendCloud\CheckoutCore\Contracts\Facades;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;
use SendCloud\SendCloud\CheckoutCore\Domain\Search\Query;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Checkout;
use SendCloud\SendCloud\CheckoutCore\Exceptions\Domain\FailedToDeleteCheckoutConfigurationException;
use SendCloud\SendCloud\CheckoutCore\Exceptions\Domain\FailedToUpdateCheckoutConfigurationException;

/**
 * Interface CheckoutService
 *
 * @package SendCloud\SendCloud\CheckoutCore\Contracts\Facades
 */
interface CheckoutService
{
    /**
     * Updates checkout configuration.
     *
     * @param Checkout $checkout
     *
     * @return void
     *
     * @throws FailedToUpdateCheckoutConfigurationException
     */
    public function update(Checkout $checkout);

    /**
     * Deletes locally saved configuration.
     *
     * @return void
     *
     * @throws FailedToDeleteCheckoutConfigurationException
     */
    public function delete();

    /**
     * Deletes all data when the uninstall is called.
     *
     * @return void
     *
     * @throws FailedToDeleteCheckoutConfigurationException
     */
    public function uninstall();

    /**
     * Provides delivery method matching the
     *
     * @param Query $query
     *
     * @return DeliveryMethod[]
     */
    public function search(Query $query);
}
