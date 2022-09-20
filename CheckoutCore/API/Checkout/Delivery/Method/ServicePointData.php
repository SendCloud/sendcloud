<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class ServicePointData extends DataTransferObject
{
    /**
     * @var string
     */
    protected $apiKey;

    /**
     * @var string
     */
    protected $country;

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * Provides array representation of a dto.
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            'api_key' => $this->apiKey,
            'country_iso_2' => $this->country
        );
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData
     * @return ServicePointData
     */
    public static function fromArray(array $rawData)
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return parent::fromArray($rawData);
    }

    /**
     * Factory template method used to instantiate data transfer object from an array of data.
     *
     * @param array $rawData
     * @return ServicePointData
     */
    public static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setApiKey($rawData['api_key']);
        $entity->setCountry($rawData['country_iso_2']);

        return $entity;
    }
}