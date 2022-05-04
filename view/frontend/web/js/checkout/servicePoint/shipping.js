define([
    'Magento_Checkout/js/model/quote',
    'jquery',
    'uiRegistry',
    'mage/translate',
], function (quote, $, uiRegistry, $t) {
    'use strict';
    return {
        /**
         * Validate service point data
         *
         * @param origResult
         * @returns {boolean|*}
         */
        successHandler: function (selectMethod) {
            var extensionData = quote.getExtensionAttributes();

            if (selectMethod && selectMethod.carrier_code === 'sendcloud_checkout' && quote.hasDeliveryMethodData()) {
                return;
            }

            uiRegistry.async("checkout.steps.shipping-step.shippingAddress")(
                function (shippingValidation) {
                    shippingValidation.errorValidationMessage($t('Please select a service point'));
                }
            );
        }
    };
});
