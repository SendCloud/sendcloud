<?php

namespace SendCloud\SendCloud\CheckoutCore\Contracts\Services;

use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryZone;

/**
 * Interface DeliveryZoneSetupService
 *
 * @package SendCloud\SendCloud\CheckoutCore\Contracts\Services
 */
interface DeliveryZoneSetupService
{
    /**
     * Deletes specified zones.
     *
     * @param DeliveryZone[] $zones
     *
     * @return void
     */
    public function deleteSpecific(array $zones);

    /**
     * Deletes all created zones in system.
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
}
