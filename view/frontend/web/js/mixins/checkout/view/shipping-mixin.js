define([
    'Magento_Checkout/js/model/quote',
    'mage/translate',
    'jquery',
    'SendCloud_SendCloud/js/servicePoint/mixins/validate',
    'SendCloud_SendCloud/js/checkout/nominatedDay/validate',
    'SendCloud_SendCloud/js/checkout/servicePoint/validate'
], function (quote, $t, $, servicePoint, nominatedDay, checkoutServicePoint) {
    'use strict';

    return function (Component) {
        return Component.extend({
            validateShippingInformation: function() {
                try{
                    var origResult = this._super();
                }
                catch(error) {
                    return false;
                }

                let method = quote.getSendcloudDeliveryMethodType();

                switch (method) {
                    case 'service_point_legacy' :
                        return servicePoint.validateServicePoint(origResult);
                    case 'nominated_day_delivery' :
                        return nominatedDay.validateShippingInformation(origResult);
                    case 'service_point_delivery' :
                        return checkoutServicePoint.validateServicePoint(origResult);
                    default:
                        return origResult;
                }
            }
        });
    }
});
