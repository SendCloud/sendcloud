<?php

namespace SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

class Holiday extends DataTransferObject
{
    /**
     * @var string
     */
    protected $frequency;

    /**
     * @var string
     */
    protected $fromDate;

    /**
     * @var bool
     */
    protected $recurring;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $toDate;

    /**
     * @return string
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param  string  $frequency
     */
    public function setFrequency($frequency)
    {
        $this->frequency = $frequency;
    }

    /**
     * @return string
     */
    public function getFromDate()
    {
        return $this->fromDate;
    }

    /**
     * @param  string  $fromDate
     */
    public function setFromDate($fromDate)
    {
        $this->fromDate = $fromDate;
    }

    /**
     * @return bool
     */
    public function isRecurring()
    {
        return $this->recurring;
    }

    /**
     * @param  bool  $recurring
     */
    public function setRecurring($recurring)
    {
        $this->recurring = $recurring;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param  string  $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getToDate()
    {
        return $this->toDate;
    }

    /**
     * @param  string  $toDate
     */
    public function setToDate($toDate)
    {
        $this->toDate = $toDate;
    }

    /**
     * Provides array representation of a dto.
     *
     * @return array Array representation.
     */
    public function toArray()
    {
        return [
            'frequency' => $this->frequency,
            'from_date' => $this->fromDate,
            'recurring' => $this->recurring,
            'title' => $this->title,
            'to_date' => $this->toDate
        ];
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return Holiday DTO instance.
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
     * @return Holiday
     */
    protected static function instantiate(array $rawData)
    {
        $entity = new static();
        $entity->setFrequency($rawData['frequency']);
        $entity->setFromDate($rawData['from_date']);
        $entity->setRecurring($rawData['recurring']);
        $entity->setTitle($rawData['title']);
        $entity->setToDate($rawData['to_date']);

        return $entity;
    }
}
