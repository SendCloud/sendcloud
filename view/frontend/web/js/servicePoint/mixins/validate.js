define([
    'Magento_Checkout/js/model/quote',
    'jquery',
], function (quote, $) {
    'use strict';
    return {
        /**
         * Validate service point data
         *
         * @param origResult
         * @returns {boolean|*}
         */
        validateServicePoint: function (origResult) {
            var servicePointData = quote.getExtensionAttributes(),
                selectedMethod = quote.shippingMethod();

            if (
                selectedMethod && selectedMethod.carrier_code === 'sendcloud' &&
                (!servicePointData || !servicePointData['sendcloud_service_point_id'])
            ) {
                var servicePointWrapper = $('#sendcloud-service-point');

                window.scrollTo({
                    top: servicePointWrapper.offset().top,
                    behavior: "smooth"
                });

                return false;
            }

            return origResult;
        }
    };
});
