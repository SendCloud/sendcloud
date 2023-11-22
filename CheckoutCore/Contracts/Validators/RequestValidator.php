<?php


namespace SendCloud\SendCloud\CheckoutCore\Contracts\Validators;

use SendCloud\SendCloud\CheckoutCore\HTTP\Request;
use SendCloud\SendCloud\CheckoutCore\Exceptions\ValidationException;

interface RequestValidator
{
    /**
     * Validates array.
     *
     * @param Request $request
     *
     * @return void
     *
     * @throws ValidationException
     */
    public function validate(Request $request);
}
