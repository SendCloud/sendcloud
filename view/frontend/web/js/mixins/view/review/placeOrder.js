define([
    'Magento_Checkout/js/model/quote',
    'SendCloud_SendCloud/js/view/checkout/shipping/servicePoint'
], function(quote, servicePoint){
    'use strict';

    return function (Component) {
        return Component.extend({
            isPlaceOrderActionAllowed: function () {
                if (quote.shippingMethod().method_code === 'sendcloud' && !servicePoint().servicePointData()) {
                    return false;
                }
                return true;
            }
        });
    }
})
