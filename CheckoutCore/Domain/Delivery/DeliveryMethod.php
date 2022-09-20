<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethods\NominatedDayDelivery;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethods\SameDayDelivery;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethods\ServicePointDelivery;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethods\StandardDelivery;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\AvailabilityPolicyFactory;
use SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Availability\Order;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Identifiable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Updateable;
use SendCloud\SendCloud\CheckoutCore\Utility\CollectionComparator;

/**
 * Class DeliveryMethod
 *
 * @package SendCloud\SendCloud\CheckoutCore\Domain\Delivery
 */
class DeliveryMethod implements DTOInstantiable, Updateable, Identifiable
{
    /**
     * Assigned by the API.
     *
     * @var string
     */
    protected $id;
    /**
     * Id assigned by the system when internal delivery method representation has been created.
     *
     * @var int | string | null
     */
    protected $systemId;
    /**
     * Reference to a delivery zone. Null if unknown.
     *
     * @var string | null
     */
    protected $deliveryZoneId;
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
     * @var Carrier|null
     */
    protected $carrier;
    /**
     * @var int
     */
    protected $senderAddressId;
    /**
     * @var bool
     */
    protected $showCarrierInfoOnCheckout;
    /**
     * @var ShippingProduct|null
     */
    protected $shippingProduct;
    /**
     * @var ShippingRateData|null
     */
    protected $shippingRateData;
    /**
     * @var HandoverDay[] | OrderPlacementDay[]
     */
    protected $processingDays;
    /**
     * @var string
     */
    protected $timeZoneName;
    /**
     * @var Holiday[]
     */
    protected $holidays;
    /**
     * @var Carrier[]
     */
    protected $carriers;
    /**
     * @var ServicePointData|null
     */
    protected $servicePointData;
    /**
     * @var string
     */
    protected $rawConfig;

    /**
     * DeliveryMethod constructor.
     *
     * @param string $id
     * @param int|string|null $systemId
     * @param string|null $deliveryZoneId
     * @param string $type
     * @param string $externalTitle
     * @param string $internalTitle
     * @param Carrier|null $carrier
     * @param int $senderAddressId
     * @param bool $showCarrierInfoOnCheckout
     * @param ShippingProduct|null $shippingProduct
     * @param ShippingRateData $shippingRateData
     * @param HandoverDay[] | OrderPlacementDay[] $processingDays
     * @param string $timeZoneName
     * @param Holiday[] $holidays
     * @param array $carriers
     * @param ServicePointData|null $servicePointData
     * @param $rawConfig
     */
    public function __construct(
        $id,
        $systemId,
        $deliveryZoneId,
        $type,
        $externalTitle,
        $internalTitle,
        $carrier,
        $senderAddressId,
        $showCarrierInfoOnCheckout,
        $shippingProduct,
        $shippingRateData,
        array $processingDays,
        $timeZoneName,
        array $holidays,
        array $carriers,
        $servicePointData,
        $rawConfig
    )
    {
        $this->id = $id;
        $this->systemId = $systemId;
        $this->deliveryZoneId = $deliveryZoneId;
        $this->type = $type;
        $this->externalTitle = $externalTitle;
        $this->internalTitle = $internalTitle;
        $this->carrier = $carrier;
        $this->showCarrierInfoOnCheckout = $showCarrierInfoOnCheckout;
        $this->shippingProduct = $shippingProduct;
        $this->shippingRateData = $shippingRateData;
        $this->processingDays = $processingDays;
        $this->timeZoneName = $timeZoneName;
        $this->holidays = $holidays;
        $this->carriers = $carriers;
        $this->servicePointData = $servicePointData;
        $this->rawConfig = $rawConfig;
        $this->senderAddressId = $senderAddressId;
    }

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
     * @return int|string|null
     */
    public function getSystemId()
    {
        return $this->systemId;
    }

    /**
     * @param int|string|null $systemId
     */
    public function setSystemId($systemId)
    {
        $this->systemId = $systemId;
    }

    /**
     * @return string|null
     */
    public function getDeliveryZoneId()
    {
        return $this->deliveryZoneId;
    }

    /**
     * @param string|null $deliveryZoneId
     */
    public function setDeliveryZoneId($deliveryZoneId)
    {
        $this->deliveryZoneId = $deliveryZoneId;
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
     * @return Carrier|null
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * @param Carrier|null $carrier
     */
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;
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
    public function isShowCarrierInfoOnCheckout()
    {
        return $this->showCarrierInfoOnCheckout;
    }

    /**
     * @param bool $showCarrierInfoOnCheckout
     */
    public function setShowCarrierInfoOnCheckout($showCarrierInfoOnCheckout)
    {
        $this->showCarrierInfoOnCheckout = $showCarrierInfoOnCheckout;
    }

    /**
     * @return ShippingProduct|null
     */
    public function getShippingProduct()
    {
        return $this->shippingProduct;
    }

    /**
     * @param ShippingProduct|null $shippingProduct
     */
    public function setShippingProduct($shippingProduct)
    {
        $this->shippingProduct = $shippingProduct;
    }

    /**
     * @return HandoverDay[] | OrderPlacementDay[]
     */
    public function getProcessingDays()
    {
        return $this->processingDays;
    }

    /**
     * @param HandoverDay[] | OrderPlacementDay[] $handoverDays
     */
    public function setProcessingDays($handoverDays)
    {
        $this->processingDays = $handoverDays;
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
     * @return Holiday[]
     */
    public function getHolidays()
    {
        return $this->holidays;
    }

    /**
     * @param Holiday[] $holidays
     */
    public function setHolidays($holidays)
    {
        $this->holidays = $holidays;
    }

    /**
     * @return ShippingRateData|null
     */
    public function getShippingRateData()
    {
        return $this->shippingRateData;
    }

    /**
     * @param ShippingRateData|null $shippingRateData
     */
    public function setShippingRateData($shippingRateData)
    {
        $this->shippingRateData = $shippingRateData;
    }

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
     * @return ServicePointData|null
     */
    public function getServicePointData()
    {
        return $this->servicePointData;
    }

    /**
     * @param ServicePointData|null $servicePointData
     */
    public function setServicePointData($servicePointData)
    {
        $this->servicePointData = $servicePointData;
    }

    /**
     * @return string
     */
    public function getRawConfig()
    {
        return $this->rawConfig;
    }

    /**
     * Checks if delivery method is available
     *
     * @return bool
     * @throws \Exception
     */
    public function isAvailable(Order $order)
    {
        $availabilityPolicy = AvailabilityPolicyFactory::create($this, $order);

        return $availabilityPolicy->isAvailable();
    }

    /**
     * Makes an instance from dto.
     *
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethod $object
     *
     * @return DeliveryMethod
     */
    public static function fromDTO($object)
    {
        if ($object instanceof NominatedDayDelivery || $object instanceof SameDayDelivery || $object instanceof StandardDelivery) {
            $holidays = array();
            foreach ($object->getHolidays() as $index => $holiday) {
                $holidays[$index] = $holiday !== null ? Holiday::fromDTO($holiday) : null;
            }

            $shippingProduct = ShippingProduct::fromDTO($object->getShippingProduct());
            $carrier = Carrier::fromDTO($object->getCarrier());

            $processingDays = array();
            if ($object instanceof NominatedDayDelivery || $object instanceof SameDayDelivery) {
                foreach ($object->getHandoverDays() as $index => $handoverDay) {
                    $processingDays[$index] = $handoverDay !== null ? HandoverDay::fromDTO($handoverDay) : null;
                }
            } else {
                foreach ($object->getOrderPlacementDays() as $index => $handoverDay) {
                    $processingDays[$index] = $handoverDay !== null ? OrderPlacementDay::fromDTO($handoverDay) : null;
                }
            }

        } else if ($object instanceof ServicePointDelivery) {
            $carriers = [];
            foreach ($object->getCarriers() as $index => $carrier) {
                $carriers[$index] = $carrier !== null ? Carrier::fromDTO($carrier) : null;
            }

            $servicePointData = ServicePointData::fromDTO($object->getServicePointData());
        }

        return new DeliveryMethod(
            $object->getId(),
            null,
            null,
            $object->getType(),
            $object->getExternalTitle(),
            $object->getInternalTitle(),
            isset($carrier) ? $carrier : null,
            $object->getSenderAddressId(),
            $object->isShowCarrierInformationInCheckout(),
            isset($shippingProduct) ? $shippingProduct : null,
            ShippingRateData::fromDTO($object->getShippingRateData()),
            isset($processingDays) ? $processingDays : array(),
            $object->getTimeZoneName(),
            isset($holidays) ? $holidays : array(),
            isset($carriers) ? $carriers : array(),
            isset($servicePointData) ? $servicePointData : null,
            json_encode($object->getRawData())
        );
    }

    /**
     * Checks whether the instance is different enough from target to require an update.
     *
     * @param DeliveryMethod $target
     * @return boolean
     */
    public function canBeUpdated($target)
    {
        $canBeUpdated = $this->getDeliveryZoneId() !== $target->getDeliveryZoneId()
            || $this->getType() !== $target->getType()
            || $this->getExternalTitle() !== $target->getExternalTitle()
            || $this->getInternalTitle() !== $target->getInternalTitle()
            || $this->getSenderAddressId() !== $target->getSenderAddressId()
            || $this->isShowCarrierInfoOnCheckout() !== $target->isShowCarrierInfoOnCheckout()
            || $this->getTimeZoneName() !== $target->getTimeZoneName()
            || $this->getRawConfig() !== $target->getRawConfig();

        if ($canBeUpdated) {
            return true;
        }

        if ($target->getType() === 'service_point_delivery') {
            return !CollectionComparator::isEqual($this->getCarriers(), $target->getCarriers())
                || !$this->getServicePointData()->isEqual($target->getServicePointData());
        }

        return !$this->getCarrier()->isEqual($target->getCarrier())
            || !$this->getShippingProduct()->isEqual($target->getShippingProduct())
            || !$this->getShippingRateData()->isEqual($target->getShippingRateData())
            || !CollectionComparator::isEqual($this->getProcessingDays(), $target->getProcessingDays())
            || !CollectionComparator::isEqual($this->getHolidays(), $target->getHolidays());
    }
}