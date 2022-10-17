<?php

namespace SendCloud\SendCloud\CheckoutCore\Contracts\Services;

interface CurrencyService
{
    /**
     * Provides default currency code in the ISO_4217 format.
     *
     * @see https://en.wikipedia.org/wiki/ISO_4217
     *
     * @return string
     */
    public function getDefaultCurrencyCode();
}
