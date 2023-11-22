<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout;

use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Zone\DeliveryZone;
use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class Checkout extends DataTransferObject
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var int
     */
    protected $integrationId;
    /**
     * @var string
     */
    protected $version;
    /**
     * @var string
     */
    protected $updatedAt;
    /**
     * @var string
     */
    protected $currency;
    /**
     * @var string
     */
    protected $minimalPluginVersion;
    /**
     * @var DeliveryZone[]
     */
    protected $deliveryZones;

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getIntegrationId()
    {
        return $this->integrationId;
    }

    /**
     * @param int $integrationId
     */
    public function setIntegrationId($integrationId)
    {
        $this->integrationId = $integrationId;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return DeliveryZone[]
     */
    public function getDeliveryZones()
    {
        return $this->deliveryZones;
    }

    /**
     * @param DeliveryZone[] $deliveryZones
     */
    public function setDeliveryZones($deliveryZones)
    {
        $this->deliveryZones = $deliveryZones;
    }

    /**
     * @return string
     */
    public function getMinimalPluginVersion()
    {
        return $this->minimalPluginVersion;
    }

    /**
     * @param  string  $minimalPluginVersion
     */
    public function setMinimalPluginVersion($minimalPluginVersion)
    {
        $this->minimalPluginVersion = $minimalPluginVersion;
    }

    /**
     * Provides array representation of a dto.
     *
     * @return array Array representation.
     */
    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'integration_id' => $this->getIntegrationId(),
            'version' => $this->getVersion(),
            'updated_at' => $this->getUpdatedAt(),
            'currency' => $this->getCurrency(),
            'minimal_plugin_version' => $this->getMinimalPluginVersion(),
            'delivery_zones' => static::toArrayBatch($this->getDeliveryZones()),
        ];
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return Checkout DTO instance.
     */
    public static function fromArray(array $rawData)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::fromArray($rawData);
    }

    /**
     * Factory template method used to instantiate data transfer object from an array of data.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return Checkout
     */
    protected static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setId($rawData['id']);
        $entity->setIntegrationId(static::getValue($rawData, 'integration_id'));
        $entity->setVersion($rawData['version']);
        $entity->setUpdatedAt($rawData['updated_at']);
        $entity->setCurrency(static::getValue($rawData, 'currency'));
        $entity->setMinimalPluginVersion($rawData['minimal_plugin_version']);
        /** @noinspection PhpParamsInspection */
        $entity->setDeliveryZones(DeliveryZone::fromArrayBatch(self::getValue($rawData, 'delivery_zones', [])));

        return $entity;
    }
}
