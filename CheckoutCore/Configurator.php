<?php

namespace SendCloud\SendCloud\CheckoutCore;

use SendCloud\SendCloud\CheckoutCore\API\Checkout\Checkout;
use SendCloud\SendCloud\CheckoutCore\HTTP\Request;
use SendCloud\SendCloud\CheckoutCore\Contracts\Facades\CheckoutService as CheckoutServiceInterface;
use SendCloud\SendCloud\CheckoutCore\Contracts\Validators\RequestValidator;

class Configurator
{
    /**
     * Request validator that validates update request.
     *
     * @var RequestValidator
     */
    protected $updateValidator;
    /**
     * Checkout service.
     *
     * @var CheckoutService
     */
    protected $checkoutService;
    /**
     * Request validator that validates delete request.
     *
     * @var RequestValidator
     */
    private $deleteValidator;

    /**
     * Configurator constructor.
     *
     * @param RequestValidator $updateValidator
     * @param RequestValidator $deleteValidator
     * @param CheckoutServiceInterface $checkoutService
     */
    public function __construct(
        RequestValidator $updateValidator,
        RequestValidator $deleteValidator,
        CheckoutServiceInterface $checkoutService
    ) {
        $this->updateValidator = $updateValidator;
        $this->checkoutService = $checkoutService;
        $this->deleteValidator = $deleteValidator;
    }

    /**
     * Updates checkout configuration.
     *
     * @param Request $request
     *
     * @return void
     *
     * @throws Exceptions\DTO\DTOValidationException
     * @throws Exceptions\Domain\FailedToUpdateCheckoutConfigurationException
     * @throws Exceptions\ValidationException
     */
    public function update(Request $request)
    {
        $this->updateValidator->validate($request);
        $body = $request->getBody();
        $this->checkoutService->update(Checkout::fromArray($body['checkout_configuration']));
    }

    /**
     * Deletes local configuration.
     *
     * @param Request $request
     *
     * @return void
     *
     * @throws Exceptions\ValidationException
     * @throws Exceptions\Domain\FailedToDeleteCheckoutConfigurationException
     */
    public function deleteAll(Request $request)
    {
        $this->deleteValidator->validate($request);
        $this->checkoutService->delete();
    }
}
