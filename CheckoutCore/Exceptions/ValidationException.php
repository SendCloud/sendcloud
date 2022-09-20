<?php

namespace SendCloud\SendCloud\CheckoutCore\Exceptions;

/**
 * Class ValidationException
 *
 * @package SendCloud\SendCloud\CheckoutCore\Exceptions
 */
class ValidationException extends BaseException
{
    /**
     * List of validation errors.
     *
     * @var array
     */
    protected $validationErrors;

    /**
     * ValidationException constructor.
     *
     * @param array $validationErrors
     * @param string $message
     * @param int $code
     * @param \Throwable $previous
     */
    public function __construct(array $validationErrors, $message = "", $code = 0, $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->validationErrors = $validationErrors;
    }

    /**
     * Provides validation errors.
     *
     * @return array List of validation errors.
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}