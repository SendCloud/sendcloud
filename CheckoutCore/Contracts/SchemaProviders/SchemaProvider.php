<?php

namespace SendCloud\SendCloud\CheckoutCore\Contracts\SchemaProviders;

interface SchemaProvider
{
    /**
     * Gets payload schema.
     *
     * @return mixed
     */
    public static function getSchema();
}