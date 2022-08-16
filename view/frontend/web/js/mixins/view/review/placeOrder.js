define([
    'Magento_Checkout/js/model/quote',
    'SendCloud_SendCloud/js/servicePoint/mixins/placeOrder',
    'SendCloud_SendCloud/js/checkout/nominatedDay/placeOrder',
    'SendCloud_SendCloud/js/checkout/servicePoint/placeOrder',
], function(quote, servicePoint, nominatedDay, checkoutServicePoint){
    'use strict';

    return function (Component) {
        return Component.extend({
            isPlaceOrderActionAllowed: function () {
                if (typeof quote.shippingMethod() !== 'undefined' && quote.shippingMethod() !== null) {

                    let method = quote.getSendcloudDeliveryMethodType();

                    switch (method) {
                        case 'service_point_legacy' :
                            return servicePoint.validateServicePoint();
                        case 'nominated_day_delivery' :
                            return nominatedDay.validateData();
                        case 'service_point_delivery' :
                            return checkoutServicePoint.validateServicePoint();
                    }
                }
                return true;
            }
        });
    }
})
