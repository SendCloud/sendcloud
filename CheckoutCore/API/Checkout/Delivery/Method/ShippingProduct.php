<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

/**
 * Class ShippingProduct
 *
 * @package SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method
 */
class ShippingProduct extends DataTransferObject
{
    /**
     * @var string
     */
    protected $code;
    /**
     * @var string
     */
    protected $name;
    /**
     * @var int
     */
    protected $leadTimeHours;

    /**
     * @var int
     */
    protected $leadTimeHoursOverride;

    /**
     * @var array
     */
    protected $selectedFunctionalities;
    /**
     * @var DeliveryDay[] | null[]
     */
    protected $carrierDeliveryDays;

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getLeadTimeHours()
    {
        return $this->leadTimeHours;
    }

    /**
     * @param int $leadTimeHours
     */
    public function setLeadTimeHours($leadTimeHours)
    {
        $this->leadTimeHours = $leadTimeHours;
    }

    /**
     * @return array
     */
    public function getSelectedFunctionalities()
    {
        return $this->selectedFunctionalities;
    }

    /**
     * @return int
     */
    public function getLeadTimeHoursOverride()
    {
        return $this->leadTimeHoursOverride;
    }

    /**
     * @param int $leadTimeHoursOverride
     */
    public function setLeadTimeHoursOverride($leadTimeHoursOverride)
    {
        $this->leadTimeHoursOverride = $leadTimeHoursOverride;
    }

    /**
     * @param array $selectedFunctionalities
     */
    public function setSelectedFunctionalities($selectedFunctionalities)
    {
        $this->selectedFunctionalities = $selectedFunctionalities;
    }

    /**
     * @return DeliveryDay[] | null[]
     */
    public function getCarrierDeliveryDays()
    {
        return $this->carrierDeliveryDays;
    }

    /**
     * @param DeliveryDay[] | null[] $carrierDeliveryDays
     */
    public function setCarrierDeliveryDays($carrierDeliveryDays)
    {
        $this->carrierDeliveryDays = $carrierDeliveryDays;
    }

    /**
     * Provides array representation of a dto.
     *
     * @return array Array representation.
     */
    public function toArray()
    {
        return array(
            'code' => $this->getCode(),
            'name' => $this->getName(),
            'lead_time_hours' => $this->getLeadTimeHours(),
            'lead_time_hours_override' => $this->getLeadTimeHoursOverride(),
            'selected_functionalities' => $this->getSelectedFunctionalities(),
            'carrier_delivery_days' => DataTransferObject::toArrayBatch($this->getCarrierDeliveryDays()),
        );
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return ShippingProduct DTO instance.
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
     * @return DataTransferObject
     */
    protected static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setName($rawData['name']);
        $entity->setCode($rawData['code']);
        $entity->setLeadTimeHours($rawData['lead_time_hours']);
        $entity->setLeadTimeHoursOverride($rawData['lead_time_hours_override']);
        $entity->setSelectedFunctionalities($rawData['selected_functionalities']);
        $entity->setCarrierDeliveryDays(DeliveryDay::fromArrayBatch(static::getValue($rawData, 'carrier_delivery_days', array())));

        return $entity;
    }
}