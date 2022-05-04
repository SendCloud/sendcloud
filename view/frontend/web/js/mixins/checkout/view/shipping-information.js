define([
    'jquery',
    'knockout',
    'Magento_Checkout/js/model/quote'
], function($, ko, quote){
    'use strict';
    return function(c){
        //if targetModule is a uiClass based objec
        var self = this;
        return c.extend({
            defaults: {
                template: 'SendCloud_SendCloud/shipping-information'
            },
            quote:quote,
            initObservable: function () {
                this._super();
                this.selectedMethod = ko.computed(function() {
                    var method = quote.shippingMethod();
                    var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;

                    if(quote.getSendcloudDeliveryMethodType() && quote.getSendcloudDeliveryMethodType() !== 'service_point_legacy'){
                        selectedMethod = method.carrier_code + '_' + quote.getSendcloudDeliveryMethodType();
                    }

                    return selectedMethod;
                }, this);

                this.extensionData = ko.computed(function() {
                    return quote.getCheckoutExtensionAttributes();
                }, this);

                return this;
            },

        });
    };
});
