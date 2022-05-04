<?php

namespace SendCloud\SendCloud\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use Magento\Store\Model\StoreManagerInterface;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Zone\DeliveryZone as DeliveryZoneDTO;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\DeliveryZone;

/**
 * Class SendcloudDeliveryZone
 * @package SendCloud\SendCloud\Model\ResourceModel
 */
class SendcloudDeliveryZone extends AbstractDomen
{
    /**
     * Resource model initialization.
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('sendcloud_shipping_zones', 'id');
    }

    /**
     * Create delivery zone
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
        $objects = array_map(function (DeliveryZone $data) {
            return [
                'external_id' => $data->getId(),
                'data' => $data->getRawConfig(),
                'store_view_id' => $this->storeManager->getStore()->getId()
            ];
        }, $objects);
        $connection->insertMultiple($this->getMainTable(), $objects);
    }

    /**
     * Update delivery zone
     *
     * @param array $objects
     * @param array $additionalData
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function update(array $objects, array $additionalData = [])
    {
        if (empty($objects)) {
            return;
        }
        $connection = $this->getConnection();
        foreach ($objects as $object) {
            $data = [
                'external_id' => $object->getId(),
                'data' => $object->getRawConfig(),
                'store_view_id' => $this->storeManager->getStore()->getId()
            ];
            $whereCondition = ['external_id=?' => $object->getId()];
            $connection->update($this->getMainTable(), $data, $whereCondition);
        }
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

        return DeliveryZone::fromDTO(DeliveryZoneDTO::fromArray($data));
    }
}
