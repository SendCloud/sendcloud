define([
    'Magento_Checkout/js/model/quote',
], function (quote) {
    'use strict';
    return {
        /**
         * Reset service point data if address is changed
         *
         * @param countryCode
         * @param zipCode
         */
        resetServicePoint: function (countryCode, zipCode) {
            if (quote.getDeliveryMethodData() &&
                quote.getDeliveryMethodData()['service_point'] &&
                (quote.getDeliveryMethodData()['service_point']['country'] !== countryCode ||
                    quote.getDeliveryMethodData()['service_point']['postal_code'] !== zipCode)) {

                quote.setDeliveryMethodData({delivery_method_data: null});
            }
        }
    };
});
