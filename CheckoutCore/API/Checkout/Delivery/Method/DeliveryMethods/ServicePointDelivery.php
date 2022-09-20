<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethods;

use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\Carrier;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethod;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\ServicePointData;
use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class ServicePointDelivery extends DeliveryMethod
{
    /**
     * @var Carrier[]
     */
    private $carriers;

    /**
     * @var ServicePointData
     */
    private $servicePointData;

    /**
     * @return Carrier[]
     */
    public function getCarriers()
    {
        return $this->carriers;
    }

    /**
     * @param Carrier[] $carriers
     */
    public function setCarriers($carriers)
    {
        $this->carriers = $carriers;
    }

    /**
     * @return ServicePointData
     */
    public function getServicePointData()
    {
        return $this->servicePointData;
    }

    /**
     * @param ServicePointData $servicePointData
     */
    public function setServicePointData($servicePointData)
    {
        $this->servicePointData = $servicePointData;
    }

    /**
     * Provides array representation of a dto.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['carriers'] = DataTransferObject::toArrayBatch($this->getCarriers());
        $array['service_point_data'] =  $this->getServicePointData()->toArray();

        return $array;
    }

    /**
     *  Set entity attributes from array
     *
     * @param array $rawData
     * @return void
     */
    protected function setEntityAttributes(array $rawData)
    {
        parent::setEntityAttributes($rawData);
        /** @noinspection PhpParamsInspection */
        $this->setCarriers(Carrier::fromArrayBatch(static::getValue($rawData, 'carriers', array())));
        $this->setServicePointData(ServicePointData::fromArray($rawData['service_point_data']));
    }
}