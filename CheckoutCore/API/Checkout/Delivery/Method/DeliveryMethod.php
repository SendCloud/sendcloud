<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method;

use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethods\DeliveryMethodFactory;
use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

/**
 * Class DeliveryMethod
 *
 * @package SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method
 */
abstract class DeliveryMethod extends DataTransferObject
{
    /**
     * @var string
     */
    protected $id;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string
     */
    protected $externalTitle;
    /**
     * @var string
     */
    protected $internalTitle;
    /**
     * @var int
     */
    protected $senderAddressId;
    /**
     * @var bool
     */
    protected $showCarrierInformationInCheckout;
    /**
     * @var string
     */
    protected $timeZoneName;
    /**
     * @var ShippingRateData
     */
    protected $shippingRateData;
    /**
     * @var array
     */
    protected $rawData;

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getExternalTitle()
    {
        return $this->externalTitle;
    }

    /**
     * @param string $externalTitle
     */
    public function setExternalTitle($externalTitle)
    {
        $this->externalTitle = $externalTitle;
    }

    /**
     * @return string
     */
    public function getInternalTitle()
    {
        return $this->internalTitle;
    }

    /**
     * @param string $internalTitle
     */
    public function setInternalTitle($internalTitle)
    {
        $this->internalTitle = $internalTitle;
    }

    /**
     * @return int
     */
    public function getSenderAddressId()
    {
        return $this->senderAddressId;
    }

    /**
     * @param int $senderAddressId
     */
    public function setSenderAddressId($senderAddressId)
    {
        $this->senderAddressId = $senderAddressId;
    }

    /**
     * @return bool
     */
    public function isShowCarrierInformationInCheckout()
    {
        return $this->showCarrierInformationInCheckout;
    }

    /**
     * @param bool $showCarrierInformationInCheckout
     */
    public function setShowCarrierInformationInCheckout($showCarrierInformationInCheckout)
    {
        $this->showCarrierInformationInCheckout = $showCarrierInformationInCheckout;
    }

    /**
     * @return string
     */
    public function getTimeZoneName()
    {
        return $this->timeZoneName;
    }

    /**
     * @param string $timeZoneName
     */
    public function setTimeZoneName($timeZoneName)
    {
        $this->timeZoneName = $timeZoneName;
    }

    /**
     * @return ShippingRateData
     */
    public function getShippingRateData()
    {
        return $this->shippingRateData;
    }

    /**
     * @param ShippingRateData $shippingRateData
     */
    public function setShippingRateData($shippingRateData)
    {
        $this->shippingRateData = $shippingRateData;
    }

    /**
     * @return array
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * @param array $rawData
     */
    public function setRawData($rawData)
    {
        $this->rawData = $rawData;
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
            'delivery_method_type' => $this->getType(),
            'external_title' => $this->getExternalTitle(),
            'internal_title' => $this->getInternalTitle(),
            'sender_address_id' => $this->getSenderAddressId(),
            'show_carrier_information_in_checkout' => $this->isShowCarrierInformationInCheckout(),
            'shipping_rate_data' => $this->getShippingRateData()->isEnabled() ? $this->getShippingRateData()->toArray() : [],
            'time_zone_name' => $this->getTimeZoneName(),
        ];
    }

    /**
     * Set entity attributes from array
     *
     * @param array $rawData
     */
    protected function setEntityAttributes(array $rawData)
    {
        $this->setId($rawData['id']);
        $this->setType($rawData['delivery_method_type']);
        $this->setExternalTitle($rawData['external_title']);
        $this->setInternalTitle($rawData['internal_title']);
        $this->setSenderAddressId($rawData['sender_address_id']);
        $this->setShowCarrierInformationInCheckout($rawData['show_carrier_information_in_checkout']);
        $this->setTimeZoneName($rawData['time_zone_name']);
        $this->setShippingRateData(ShippingRateData::fromArray(static::getValue($rawData, 'shipping_rate_data', [])));
        $this->setRawData($rawData);
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return DeliveryMethod DTO instance.
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
     * @return DeliveryMethod
     */
    protected static function instantiate(array $rawData)
    {
        $entity = DeliveryMethodFactory::create(static::getValue($rawData, 'delivery_method_type'));
        $entity->setEntityAttributes($rawData);

        return $entity;
    }
}
