<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Identifiable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Updateable;

/**
 * Class DeliveryZone
 *
 * @package SendCloud\SendCloud\CheckoutCore\Domain\Delivery
 */
class DeliveryZone implements DTOInstantiable, Updateable, Identifiable
{
    /**
     * Assigned by the API.
     *
     * @var string
     */
    protected $id;
    /**
     * Id assigned by the system when internal zone representation has been created.
     *
     * @var int | string | null
     */
    protected $systemId;
    /**
     * @var Country
     */
    protected $country;
    /**
     * @var string
     */
    private $rawConfig;

    /**
     * DeliveryZone constructor.
     *
     * @param string $id
     * @param int|string|null $systemId
     * @param Country $country
     * @param string $rawConfig
     */
    public function __construct($id, $systemId, Country $country, $rawConfig)
    {
        $this->id = $id;
        $this->systemId = $systemId;
        $this->country = $country;
        $this->rawConfig = $rawConfig;
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
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param Country $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return string
     */
    public function getRawConfig()
    {
        return $this->rawConfig;
    }

    /**
     * Makes an instance from dto.
     *
     * @param \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Zone\DeliveryZone $object
     *
     * @return DeliveryZone
     */
    public static function fromDTO($object)
    {
        $country = Country::fromDTO($object->getLocation());

        return new static($object->getId(), null, $country, json_encode($object->toArray()));
    }

    /**
     * Checks whether the instance is different enough from target to require an update.
     *
     * @param DeliveryZone $target
     * @return boolean
     */
    public function canBeUpdated($target)
    {
        return !$this->getCountry()->isEqual($target->getCountry());
    }
}