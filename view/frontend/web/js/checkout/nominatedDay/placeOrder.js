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

            if (selectedMethod && selectedMethod.carrier_code === 'sendcloudcheckout' && quote.hasDeliveryMethodData()) {
                return true;
            }

            return false;
        }
    };
});
