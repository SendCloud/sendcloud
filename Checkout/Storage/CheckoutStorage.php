<?php

namespace SendCloud\SendCloud\Checkout\Storage;

use SendCloud\SendCloud\CheckoutCore\Contracts\Storage\CheckoutStorage as CheckoutStorageInterface;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryZone;
use SendCloud\SendCloud\Logger\SendCloudLogger;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryMethod;
use SendCloud\SendCloud\Model\ResourceModel\SendcloudDeliveryZone;

class CheckoutStorage implements CheckoutStorageInterface
{
    /**
     * @var SendcloudDeliveryZone
     */
    private $sendcloudDeliveryZoneResourceModel;
    /**
     * @var SendcloudDeliveryMethod
     */
    private $sendcloudDeliveryMethodResourceModel;

    /**
     * @var SendCloudLogger
     */
    private $logger;

    /**
     * CheckoutStorage constructor.
     * @param SendcloudDeliveryZone $sendcloudDeliveryZoneResourceModel
     * @param SendcloudDeliveryMethod $sendcloudDeliveryMethodResourceModel
     * @param SendCloudLogger $logger
     */
    public function __construct(
        SendcloudDeliveryZone $sendcloudDeliveryZoneResourceModel,
        SendcloudDeliveryMethod $sendcloudDeliveryMethodResourceModel,
        SendCloudLogger $logger
    ) {
        $this->sendcloudDeliveryZoneResourceModel = $sendcloudDeliveryZoneResourceModel;
        $this->sendcloudDeliveryMethodResourceModel = $sendcloudDeliveryMethodResourceModel;
        $this->logger = $logger;
    }

    /**
     * Provides all delivery zone configurations.
     *
     * @return \SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryZone[]|void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function findAllZoneConfigs()
    {
        $zones = $this->sendcloudDeliveryZoneResourceModel->selectAll();
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::findAllZoneConfigs(): " . json_encode($zones)
        );

        return $zones;
    }

    /**
     * Deletes specified zone configurations.
     *
     * @param array $ids
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteSpecificZoneConfigs(array $ids)
    {
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::deleteSpecificZoneConfigs(): " . json_encode($ids)
        );
        $this->sendcloudDeliveryZoneResourceModel->deleteOne($ids);
    }

    /**
     * Deletes all saved zone configurations.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteAllZoneConfigs()
    {
        $this->sendcloudDeliveryZoneResourceModel->deleteAll();
    }

    /**
     * Updates saved zone configurations.
     *
     * @param array $zones
     */
    public function updateZoneConfigs(array $zones)
    {
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::updateZoneConfigs(): " . json_encode($zones)
        );
        $this->sendcloudDeliveryZoneResourceModel->update($zones);
    }

    /**
     * Creates delivery zones.
     *
     * @param array $zones
     */
    public function createZoneConfigs(array $zones)
    {
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::createZoneConfigs(): " . json_encode($zones)
        );
        $this->sendcloudDeliveryZoneResourceModel->create($zones);
    }

    /**
     * Provides all delivery method configurations.
     *
     * @return array|\SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod[]
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function findAllMethodConfigs()
    {
        $methods = $this->sendcloudDeliveryMethodResourceModel->selectAll();
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::findAllMethodConfigs(): " . json_encode($methods)
        );

        return $methods;
    }

    /**
     * Deletes methods identified by the provided batch of ids.
     *
     * @param array $ids
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteSpecificMethodConfigs(array $ids)
    {
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::deleteSpecificMethodConfigs(): " . json_encode($ids)
        );
        $this->sendcloudDeliveryMethodResourceModel->deleteOne($ids);
    }

    /**
     * Deletes all delivery method configurations.
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteAllMethodConfigs()
    {
        $this->sendcloudDeliveryMethodResourceModel->deleteAll();
    }

    /**
     * Updates saved delivery methods.
     *
     * @param array $methods
     */
    public function updateMethodConfigs(array $methods)
    {
        $additionalData = $this->getAdditionalData($methods);
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::updateMethodConfigs(): " .
            "methods: ". json_encode($methods) .
            "additional data: " . json_encode($additionalData)
        );
        $this->sendcloudDeliveryMethodResourceModel->update($methods, $additionalData);
    }

    /**
     * Creates method configurations.
     *
     * @param array $methods
     */
    public function createMethodConfigs(array $methods)
    {
        $additionalData = $this->getAdditionalData($methods);
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::createMethodConfigs(): " .
            "methods: ". json_encode($methods) .
            "additional data: " . json_encode($additionalData)
        );
        $this->sendcloudDeliveryMethodResourceModel->create($methods, $additionalData);
    }

    /**
     * Deletes all delivery method data generated by the integration.
     */
    public function deleteAllMethodData()
    {
        // Intentionally left empty.
    }

    /**
     * Provides delivery zones with specified ids.
     *
     * @param array $ids
     * @return \SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryZone[]
     */
    public function findZoneConfigs(array $ids)
    {
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::createMethodConfigs(): " . json_encode($ids)
        );

        return $this->sendcloudDeliveryZoneResourceModel->find($ids);
    }

    /**
     * Finds delivery methods for specified zone ids.
     *
     * @param array $zoneIds
     * @return \SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod[]|void
     */
    public function findMethodInZones(array $zoneIds)
    {
        $methods = $this->sendcloudDeliveryMethodResourceModel->findInZones($zoneIds);
        $this->logger->info(
            "SendCloud\SendCloud\Checkout\Storage\CheckoutStorage::findMethodInZones(): " .
            "zones: " . json_encode($zoneIds) .
            "methods: " . json_encode($methods)
        );

        return $methods;
    }

    /**
     * Delete delivery method configs for delivery methods that no longer exist in system.
     */
    public function deleteObsoleteMethodConfigs()
    {
        //
    }

    /**
     * Delete delivery zone configs for delivery zones that no longer exist in system.
     */
    public function deleteObsoleteZoneConfigs()
    {
        //
    }

    /**
     * @param array $methods
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getAdditionalData(array $methods): array
    {
        $additionalData = [];
        $zones = [];
        /**
         * @var DeliveryMethod $method
         */
        foreach ($methods as $method) {
            $zones[] = $method->getDeliveryZoneId();
        }
        $deliveryZones = $this->sendcloudDeliveryZoneResourceModel->select($zones);
        /**
         * @var DeliveryZone $deliveryZone
         */
        foreach ($deliveryZones as $deliveryZone) {
            $additionalData[$deliveryZone->getId()] = $deliveryZone->getCountry()->getName();
        }
        return $additionalData;
    }
}
