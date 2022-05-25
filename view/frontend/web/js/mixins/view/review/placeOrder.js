define([
    'Magento_Checkout/js/model/quote',
], function(quote){
    'use strict';

    return function (Component) {
        return Component.extend({
            isPlaceOrderActionAllowed: function () {
                if (typeof quote.shippingMethod() !== 'undefined' && quote.shippingMethod() !== null) {
                    if (quote.shippingMethod().method_code === 'sendcloud' && !quote.getSendcloudServicePoint()) {
                        return false;
                    }
                }
                return true;
            }
        });
    }
})
