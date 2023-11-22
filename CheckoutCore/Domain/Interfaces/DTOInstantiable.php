<?php

namespace SendCloud\SendCloud\CheckoutCore\Domain\Interfaces;

use SendCloud\SendCloud\CheckoutCore\DTO\DataTransferObject;

interface DTOInstantiable
{
    /**
     * Makes an instance from dto.
     *
     * @param DataTransferObject $object
     *
     * @return mixed
     */
    public static function fromDTO($object);
}
