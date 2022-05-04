<?php

namespace SendCloud\SendCloud\Checkout\Services;

use SendCloud\SendCloud\CheckoutCore\Contracts\Services\DeliveryMethodSetupService as DeliveryMethodSetupServiceInterface;

/**
 * Class DeliveryMethodSetupService
 * @package SendCloud\SendCloud\Checkout\Services
 */
class DeliveryMethodSetupService implements DeliveryMethodSetupServiceInterface
{

    public function findDiff(array $newDeliveryMethods)
    {
        // TODO: Implement findDiff() method.
    }

    public function findInZones(array $zoneIds)
    {
        // TODO: Implement findInZones() method.
    }

    public function deleteSpecific(array $methods)
    {
        // TODO: Implement deleteSpecific() method.
    }

    public function deleteAll()
    {
        // TODO: Implement deleteAll() method.
    }

    public function update(array $methods)
    {
        // TODO: Implement update() method.
    }

    public function create(array $methods)
    {
        // TODO: Implement create() method.
    }

    public function deleteAllData()
    {
        // TODO: Implement deleteAllData() method.
    }

    public function deleteObsoleteConfigs()
    {
        // TODO: Implement deleteObsoleteConfigs() method.
    }
}
