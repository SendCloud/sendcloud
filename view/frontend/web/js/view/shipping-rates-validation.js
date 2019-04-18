define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/shipping-rates-validator',
        'Magento_Checkout/js/model/shipping-rates-validation-rules',
        'SendCloud_SendCloud/js/model/shipping-rates-validator',
        'SendCloud_SendCloud/js/model/shipping-rates-validation-rules'
    ],
    function (
        Component,
        defaultShippingRatesValidator,
        defaultShippingRatesValidationRules,
        shippingRatesValidator,
        shippingRatesValidationRules
    ) {
        'use strict';
        defaultShippingRatesValidator.registerValidator('sendcloud', shippingRatesValidator);
        defaultShippingRatesValidationRules.registerRules('sendcloud', shippingRatesValidationRules)

        return Component;
    }
);