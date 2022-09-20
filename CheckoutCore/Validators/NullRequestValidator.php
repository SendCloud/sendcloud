<?php

namespace SendCloud\SendCloud\CheckoutCore\Validators;

use SendCloud\SendCloud\CheckoutCore\Contracts\Validators\RequestValidator;
use SendCloud\SendCloud\CheckoutCore\HTTP\Request;

class NullRequestValidator implements RequestValidator
{
    /**
     * Omits validation if validation for particular request type is not necessary.
     *
     * @param Request $request
     */
    public function validate(Request $request)
    {
        // Intentionally left empty.
    }
}