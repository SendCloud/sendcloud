<?php

namespace SendCloud\SendCloud\CheckoutCore\Services;

use SendCloud\SendCloud\CheckoutCore\Contracts\Services\DeliveryMethodService as BaseService;
use SendCloud\SendCloud\CheckoutCore\Contracts\Storage\CheckoutStorage;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;
use SendCloud\SendCloud\CheckoutCore\Utility\ArrayToHashMap;

class DeliveryMethodService implements BaseService
{
    /**
     * @var CheckoutStorage
     */
    protected $storage;

    /**
     * DeliveryMethodService constructor.
     * @param CheckoutStorage $storage
     */
    public function __construct(CheckoutStorage $storage)
    {
        $this->storage = $storage;
    }

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
    public function findDiff(array $newDeliveryMethods)
    {
        $newHashMap = ArrayToHashMap::convert($newDeliveryMethods);
        $existingHashMap = ArrayToHashMap::convert($this->storage->findAllMethodConfigs());

        $new = [];
        $changed = [];
        $deleted = [];

        foreach ($newHashMap as $newMethod) {
            if (!empty($existingHashMap[$newMethod->getId()])) {
                $existing = $existingHashMap[$newMethod->getId()];
                if ($newMethod->canBeUpdated($existing)) {
                    $newMethod->setSystemId($existing->getSystemId());
                    $newMethod->setDeliveryZoneId($existing->getDeliveryZoneId());
                    $changed[] = $newMethod;
                }
            } else {
                $new[] = $newMethod;
            }
        }

        // Identify deleted methods.
        foreach ($existingHashMap as $existing) {
            if (empty($newHashMap[$existing->getId()])) {
                $deleted[] = $existing;
            }
        }

        return [
            'new' => $new,
            'changed' => $changed,
            'deleted' => $deleted,
        ];
    }

    /**
     * Finds delivery methods in specific zones.
     *
     * @param array $zoneIds
     *
     * @return DeliveryMethod[]
     */
    public function findInZones(array $zoneIds)
    {
        return $this->storage->findMethodInZones($zoneIds);
    }

    /**
     * Deletes delivery methods in the specified batch.
     *
     * @param DeliveryMethod[] $methods
     *
     * @return void
     */
    public function deleteSpecific(array $methods)
    {
        $ids = array_map(function (DeliveryMethod $method) {
            return $method->getId();
        }, $methods);
        $this->storage->deleteSpecificMethodConfigs($ids);
    }

    /**
     * Deletes all delivery methods.
     *
     * @return void
     */
    public function deleteAll()
    {
        $this->storage->deleteAllMethodConfigs();
    }

    /**
     * Updates delivery methods.
     *
     * @param DeliveryMethod[] $methods
     *
     * @return void
     */
    public function update(array $methods)
    {
        $this->storage->updateMethodConfigs($methods);
    }

    /**
     * Creates delivery methods.
     *
     * @param DeliveryMethod[] $methods
     *
     * @return void
     */
    public function create(array $methods)
    {
        $this->storage->createMethodConfigs($methods);
    }

    /**
     * Deletes all data generated by the integration.
     *
     * @return void
     */
    public function deleteAllData()
    {
        $this->storage->deleteAllMethodData();
    }

    /**
     * Delete delivery method configs for delivery methods that no longer exist in system.
     *
     * @return void
     */
    public function deleteObsoleteConfigs()
    {
        $this->storage->deleteObsoleteMethodConfigs();
    }
}
