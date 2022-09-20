<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;
use SendCloud\SendCloud\CheckoutCore\Utility\CollectionComparator;

/**
 * Class ShippingProduct
 *
 * @package SendCloud\SendCloud\CheckoutCore\Domain\Delivery
 */
class ShippingProduct implements Comparable, DTOInstantiable
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
    protected $deliveryDays;

    /**
     * ShippingProduct constructor.
     * @param string $code
     * @param string $name
     * @param int $leadTimeHours
     * @param int $leadTimeHoursOverride
     * @param array $selectedFunctionalities
     * @param DeliveryDay[] | null[] $deliveryDays
     */
    public function __construct(
        $code,
        $name,
        $leadTimeHours,
        $leadTimeHoursOverride,
        array $selectedFunctionalities,
        array $deliveryDays
    )
    {
        $this->code = $code;
        $this->name = $name;
        $this->selectedFunctionalities = $selectedFunctionalities;
        $this->deliveryDays = $deliveryDays;
        $this->leadTimeHours = $leadTimeHours;
        $this->leadTimeHoursOverride = $leadTimeHoursOverride;
    }

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
     * @return array
     */
    public function getSelectedFunctionalities()
    {
        return $this->selectedFunctionalities;
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
    public function getDeliveryDays()
    {
        return $this->deliveryDays;
    }

    /**
     * @param DeliveryDay[] | null[] $deliveryDays
     */
    public function setDeliveryDays($deliveryDays)
    {
        $this->deliveryDays = $deliveryDays;
    }

    /**
     * Compares current instance to a target.
     *
     * @param ShippingProduct $target
     *
     * @return boolean
     */
    public function isEqual($target)
    {
        return $this->getLeadTimeHours() === $target->getLeadTimeHours()
            && $this->getCode() === $target->getCode()
            && $this->getName() === $target->getName()
            && $this->getSelectedFunctionalities() === $target->getSelectedFunctionalities()
            && CollectionComparator::isEqual($this->getDeliveryDays(), $target->getDeliveryDays());
    }

    /**
     * Makes an instance from dto.
     *
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\ShippingProduct $object
     *
     * @return ShippingProduct
     */
    public static function fromDTO($object)
    {
        $deliveryDays = array();
        foreach ($object->getCarrierDeliveryDays() as $index => $day) {
            $deliveryDays[$index] = $day !== null ? DeliveryDay::fromDTO($day) : null;
        }

        return new ShippingProduct(
            $object->getCode(),
            $object->getName(),
            $object->getLeadTimeHours(),
            $object->getLeadTimeHoursOverride(),
            $object->getSelectedFunctionalities(),
            $deliveryDays
        );
    }
}