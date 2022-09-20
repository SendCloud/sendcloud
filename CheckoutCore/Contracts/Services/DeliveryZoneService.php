<?php

namespace SendCloud\SendCloud\CheckoutCore\Contracts\Services;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryZone;
use SendCloud\SendCloud\CheckoutCore\Domain\Search\Query;

/**
 * Interface DeliveryZoneService
 *
 * @package SendCloud\SendCloud\CheckoutCore\Contracts\Services
 */
interface DeliveryZoneService
{
    /**
     * Finds difference between new and existing delivery zones.
     *
     * @param DeliveryZone[] $newDeliveryZones
     *
     * @return array Returns the array with identified changes:
     *      [
     *          'new' => DeliveryZone[], // List of new zones that are not yet created in the system.
     *          'changed' => DeliveryZone[], // List of existing zones that have been changed.
     *          'deleted' => DeliveryZone[], // List of existing zones that were not present in the provided list.
     *      ]
     */
    public function findDiff(array $newDeliveryZones);

    /**
     * Deletes specified zones.
     *
     * @param DeliveryZone[] $zones
     *
     * @return void
     */
    public function deleteSpecific(array $zones);

    /**
     * Deletes all saved zone configurations.
     *
     * @return void
     */
    public function deleteAll();

    /**
     * Updates delivery zones.
     *
     * @param DeliveryZone[] $zones
     *
     * @return void
     */
    public function update(array $zones);

    /**
     * Creates delivery zones.
     *
     * @param DeliveryZone[] $zones
     *
     * @return void
     */
    public function create(array $zones);

    /**
     * Provides zones matching given query with specified ids.
     *
     * @param Query $query
     *
     * @return DeliveryZone[]
     */
    public function search(Query $query);

    /**
     * Delete delivery zone configs for delivery zones that no longer exist in system.
     *
     * @return void
     */
    public function deleteObsoleteConfigs();
}