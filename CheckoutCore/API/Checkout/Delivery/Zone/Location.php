<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Zone;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

/**
 * Class Location
 *
 * @package SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Zone
 */
class Location extends DataTransferObject
{
    /**
     * ISO-2 Country code.
     *
     * @var string
     */
    protected $isoCode;
    /**
     * @var string
     */
    protected $name;

    /**
     * @return string
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * @param string $isoCode
     */
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;
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
     * Provides array representation of a dto.
     *
     * @return array Array representation.
     */
    public function toArray()
    {
        return array(
            'country' => array(
                'iso_2' => $this->getIsoCode(),
                'name' => $this->getName(),
            ),
        );
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return Location DTO instance.
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
     * @return Location
     */
    protected static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setIsoCode($rawData['country']['iso_2']);
        $entity->setName($rawData['country']['name']);

        return $entity;
    }
}