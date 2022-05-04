define([
    'Magento_Checkout/js/model/quote',
    'jquery',
], function (quote, $) {
    'use strict';
    return {
        /**
         * Validate checkout data
         *
         * @param origResult
         * @returns {boolean|*}
         */
        validateShippingInformation: function (origResult) {
            let selectedMethod = quote.shippingMethod();

            if (selectedMethod && selectedMethod.carrier_code === 'sendcloud_checkout' && quote.hasDeliveryMethodData()) {
                return origResult;
            }

            return false;
        }
    };
});
