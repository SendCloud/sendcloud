<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethods;

use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\Carrier;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\DeliveryMethod;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\HandoverDay;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\Holiday;
use SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\ShippingProduct;
use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class NominatedDayDelivery extends DeliveryMethod
{
    /**
     * @var Carrier
     */
    protected $carrier;

    /**
     * @var ShippingProduct
     */
    protected $shippingProduct;

    /**
     * @var HandoverDay[]
     */
    private $handoverDays;

    /**
     * @var Holiday[]
     */
    protected $holidays;

    /**
     * @return Carrier
     */
    public function getCarrier()
    {
        return $this->carrier;
    }

    /**
     * @param Carrier $carrier
     */
    public function setCarrier($carrier)
    {
        $this->carrier = $carrier;
    }

    /**
     * @return ShippingProduct
     */
    public function getShippingProduct()
    {
        return $this->shippingProduct;
    }

    /**
     * @param ShippingProduct $shippingProduct
     */
    public function setShippingProduct($shippingProduct)
    {
        $this->shippingProduct = $shippingProduct;
    }

    /**
     * @return HandoverDay[]
     */
    public function getHandoverDays()
    {
        return $this->handoverDays;
    }

    /**
     * @param HandoverDay[] $handoverDays
     */
    public function setHandoverDays($handoverDays)
    {
        $this->handoverDays = $handoverDays;
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
     * Provides array representation of a dto.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();
        $array['carrier'] = $this->getCarrier()->toArray();
        $array['shipping_product'] = $this->getShippingProduct()->toArray();
        $array['parcel_handover_days'] = DataTransferObject::toArrayBatch($this->getHandoverDays());

        if ($this->getHolidays()) {
            $array['holidays'] = DataTransferObject::toArrayBatch($this->getHolidays());
        }

        return $array;
    }

    /**
     * Set entity attributes from array
     *
     * @param  array  $rawData
     */
    protected function setEntityAttributes(array $rawData)
    {
        parent::setEntityAttributes($rawData);
        $this->setCarrier(Carrier::fromArray($rawData['carrier']));
        $this->setShippingProduct(ShippingProduct::fromArray($rawData['shipping_product']));
        /** @noinspection PhpParamsInspection */
        $this->setHandoverDays(HandoverDay::fromArrayBatch(static::getValue(
            $rawData,
            'parcel_handover_days',
            []
        )));
        /** @noinspection PhpParamsInspection */
        $this->setHolidays(Holiday::fromArrayBatch(static::getValue($rawData, 'holidays', [])));
    }
}
