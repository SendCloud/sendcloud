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

    defaultShippingRatesValidator.registerValidator('sendcloud_checkout', sendcloudShippingRatesValidator);
    defaultShippingRatesValidationRules.registerRules('sendcloud_checkout', sendcloudShippingRatesValidationRules);

    return Component;
});