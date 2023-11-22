<?php

namespace SendCloud\SendCloud\CheckoutCore\HTTP;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

/**
 * Class Request
 *
 * Internal request representation used to bridge an integration's request and the library.
 *
 */
class Request extends DataTransferObject
{
    /**
     * Json decoded body.
     *
     * @var array
     */
    protected $body;
    /**
     * List of request headers.
     *
     * @var array
     */
    protected $headers;

    /**
     * Request constructor.
     * @param array $body
     * @param array $headers
     */
    public function __construct(array $body, array $headers)
    {
        $this->body = $body;
        $this->headers = $headers;
    }

    /**
     * Provides request body.
     *
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Provides request headers.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Provides array representation of a dto.
     *
     * @return array Array representation.
     */
    public function toArray()
    {
        return [
            'headers' => $this->getHeaders(),
            'body' => $this->getBody(),
        ];
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return Request DTO instance.
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
     * @return Request
     */
    protected static function instantiate(array $rawData)
    {
        return new Request($rawData['body'], $rawData['headers']);
    }
}
