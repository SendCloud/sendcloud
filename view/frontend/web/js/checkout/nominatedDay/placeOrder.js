define([
    'Magento_Checkout/js/model/quote',
], function (quote) {
    'use strict';
    return {
        /**
         * Validate checkout data
         *
         * @returns {boolean}
         */
        validateData: function () {
            let selectedMethod = quote.shippingMethod();

            if (selectedMethod && selectedMethod.carrier_code === 'sendcloud_checkout' && quote.hasDeliveryMethodData()) {
                return true;
            }

            return false;
        }
    };
});
