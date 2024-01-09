<?php

namespace SendCloud\SendCloud\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethod as DeliveryMethodDTO;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryMethod;

/**
 * Class SendcloudDeliveryMethod
 * @package SendCloud\SendCloud\Model\ResourceModel
 */
class SendcloudDeliveryMethod extends AbstractDomen
{
    const SERVICE_POINT_DELIVERY = 'service_point_delivery';
    const STANDARD_DELIVERY = 'standard_delivery';

    /**
     * Create delivery method
     *
     * @param array $objects
     * @param array $additionalData
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function create(array $objects, array $additionalData = [])
    {
        if (empty($objects)) {
            return;
        }
        $connection = $this->getConnection();
        $objects = array_map(function (DeliveryMethod $data) use ($additionalData) {
            $shippingProduct = $data->getShippingProduct() ? $data->getShippingProduct()->getName() : '';
            return [
                'external_id' => $data->getId(),
                'data' => $data->getRawConfig(),
                'delivery_zone_id' => $data->getDeliveryZoneId(),
                'store_view_id' => $this->storeManager->getStore()->getId(),
                'country' => $additionalData[$data->getDeliveryZoneId()],
                'internal_title' => $data->getInternalTitle(),
                'external_title' => $data->getExternalTitle(),
                'shipping_product' => $shippingProduct,
                'rates_enabled' => $data->getShippingRateData()->isEnabled(),
                'delivery_method' => $data->getType()
            ];
        }, $objects);
        $connection->insertMultiple($this->getMainTable(), $objects);
    }

    /**
     * Update delivery method
     *
     * @param array $objects
     * @param array $additionalData
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function update(array $objects, array $additionalData = [])
    {
        if (empty($objects)) {
            return;
        }
        $connection = $this->getConnection();
        foreach ($objects as $data) {
            $shippingProduct = $data->getShippingProduct() ? $data->getShippingProduct()->getName() : '';
            $object = [
                'external_id' => $data->getId(),
                'data' => $data->getRawConfig(),
                'delivery_zone_id' => $data->getDeliveryZoneId(),
                'store_view_id' => $this->storeManager->getStore()->getId(),
                'country' => $additionalData[$data->getDeliveryZoneId()],
                'internal_title' => $data->getInternalTitle(),
                'external_title' => $data->getExternalTitle(),
                'shipping_product' => $shippingProduct,
                'rates_enabled' => $data->getShippingRateData()->isEnabled(),
                'delivery_method' => $data->getType()
            ];
            $whereCondition = ['external_id=?' => $data->getId()];
            $connection->update($this->getMainTable(), $object, $whereCondition);
        }
    }

    /**
     * Finds delivery methods in delivery zones.
     *
     * @param array $zoneIds
     * @return array
     */
    public function findInZones(array $zoneIds)
    {
        if (empty($zoneIds)) {
            return [];
        }

        $connection = $this->getConnection();

        $select = $connection->select()
            ->from($this->getMainTable())
            ->where('delivery_zone_id IN (?)', $zoneIds);

        $result = $connection->fetchAll($select);

        return array_map([$this, 'transformToEntity'], $result);
    }

    /**
     * Resource model initialization.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sendcloud_shipping_methods', 'id');
    }

    /**
     * Transforms raw data from db to entity class.
     *
     * @param array $raw
     * @return object|void
     */
    protected function transformToEntity(array $raw)
    {
        $data = json_decode($raw['data'], true);

        $deliveryMethod = DeliveryMethod::fromDTO(DeliveryMethodDTO::fromArray($data));
        $deliveryMethod->setDeliveryZoneId($raw['delivery_zone_id']);

        return $deliveryMethod;
    }
}
