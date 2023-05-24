define([
    'Magento_Checkout/js/model/quote',
], function (quote) {
    'use strict';
    return {
        /**
         * Validate service point
         *
         * @returns {boolean}
         */
        validateServicePoint: function () {
            let selectedMethod = quote.shippingMethod();

            if (selectedMethod && selectedMethod.carrier_code === 'sendcloudcheckout' && quote.hasDeliveryMethodData()) {
                return true;
            }

            return false;
        }
    };
});
