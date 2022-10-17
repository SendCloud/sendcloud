<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Delivery;

use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\Comparable;
use SendCloud\SendCloud\CheckoutCore\Domain\Interfaces\DTOInstantiable;

class Holiday implements Comparable, DTOInstantiable
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
     * Holiday constructor.
     *
     * @param  string  $frequency
     * @param  string  $fromDate
     * @param  bool  $recurring
     * @param  string  $title
     * @param  string  $toDate
     */
    public function __construct($frequency, $fromDate, $recurring, $title, $toDate)
    {
        $this->frequency = $frequency;
        $this->fromDate = $fromDate;
        $this->recurring = $recurring;
        $this->title = $title;
        $this->toDate = $toDate;
    }

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
     * Compares current instance to a target.
     *
     * @param  Holiday  $target
     *
     * @return bool
     */
    public function isEqual($target)
    {
        return $this->getFrequency() === $target->getFrequency()
               && $this->getFromDate() === $target->getFromDate()
               && $this->isRecurring() === $target->isRecurring()
               && $this->getTitle() === $target->getTitle()
               && $this->getToDate() === $target->getToDate();
    }

    /**
     * Makes an instance from dto.
     *
     * @param  \SendCloud\SendCloud\CheckoutCore\API\Checkout\Delivery\Method\Holiday  $object
     *
     * @return \SendCloud\SendCloud\CheckoutCore\Domain\Delivery\Holiday
     */
    public static function fromDTO($object)
    {
        return new self($object->getFrequency(), $object->getFromDate(), $object->isRecurring(), $object->getTitle(), $object->getToDate());
    }
}
