<?php

namespace SendCloud\SendCloud\CheckoutCore\Contracts\Services;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;

interface DeliveryMethodService
{
    /**
     * Finds difference between new and existing delivery methods.
     *
     * @param DeliveryMethod[] $newDeliveryMethods
     *
     * @return array Returns the array with identified changes:
     *      [
     *          'new' => DeliveryMethod[], // List of new methods that are not yet created in the system.
     *          'changed' => DeliveryMethod[], // List of existing methods that have been changed.
     *          'deleted' => DeliveryMethod[], // List of existing methods that were not present in the provided list.
     *      ]
     */
    public function findDiff(array $newDeliveryMethods);

    /**
     * Finds delivery methods in specific zones.
     *
     * @param array $zoneIds
     *
     * @return DeliveryMethod[]
     */
    public function findInZones(array $zoneIds);

    /**
     * Deletes delivery methods in the specified batch.
     *
     * @param DeliveryMethod[] $methods
     *
     * @return void
     */
    public function deleteSpecific(array $methods);

    /**
     * Deletes all delivery methods.
     *
     * @return void
     */
    public function deleteAll();

    /**
     * Updates delivery methods.
     *
     * @param DeliveryMethod[] $methods
     *
     * @return void
     */
    public function update(array $methods);

    /**
     * Creates delivery methods.
     *
     * @param DeliveryMethod[] $methods
     *
     * @return void
     */
    public function create(array $methods);

    /**
     * Deletes all data generated by the integration.
     *
     * @return void
     */
    public function deleteAllData();

    /**
     * Delete delivery method configs for delivery methods that no longer exist in system.
     *
     * @return void
     */
    public function deleteObsoleteConfigs();
}
