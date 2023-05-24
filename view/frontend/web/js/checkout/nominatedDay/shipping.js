define([
    'Magento_Checkout/js/model/quote',
    'jquery',
    'uiRegistry',
    'mage/translate',
], function (quote, $, uiRegistry, $t) {
    'use strict';
    return {
        /**
         * Validate checkout data
         *
         * @param origResult
         * @returns {boolean|*}
         */
        successHandler: function (selectMethod) {

            if (selectMethod && selectMethod.carrier_code === 'sendcloudcheckout' && quote.hasDeliveryMethodData()) {
                return;
            }

            uiRegistry.async("checkout.steps.shipping-step.shippingAddress")(
                function (shippingValidation) {
                    shippingValidation.errorValidationMessage($t('Please select a delivery day'));
                }
            );
        }
    };
});
