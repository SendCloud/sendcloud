define([
    'uiComponent',
    'Magento_Checkout/js/model/shipping-rates-validator',
    'Magento_Checkout/js/model/shipping-rates-validation-rules',
    'SendCloud_SendCloud/js/model/shipping-rates-validator',
    'SendCloud_SendCloud/js/model/shipping-rates-validation-rules'
], function (
    Component,
    defaultShippingRatesValidator,
    defaultShippingRatesValidationRules,
    sendcloudShippingRatesValidator,
    sendcloudShippingRatesValidationRules
) {
    'use strict';

    defaultShippingRatesValidator.registerValidator('sendcloudcheckout', sendcloudShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('sendcloudcheckout', sendcloudShippingRatesValidationRules);

    return Component;
});