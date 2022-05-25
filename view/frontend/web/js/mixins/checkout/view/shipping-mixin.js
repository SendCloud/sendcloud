define([
    'Magento_Checkout/js/model/quote',
    'mage/translate',
    'jquery'
], function (quote, $t, $) {
    'use strict';

    return function (Component) {
        return Component.extend({
            validateShippingInformation: function() {
                try{
                    var origResult = this._super(),
                        servicePointData = quote.getSendcloudServicePoint(),
                        selectedMethod = quote.shippingMethod();
                }
                catch(error) {
                    return false;
                }

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
        });
    }
});
