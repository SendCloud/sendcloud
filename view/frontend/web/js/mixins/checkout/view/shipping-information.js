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
            initObservable: function () {
                this.selectedMethod = ko.computed(function() {
                    var method = quote.shippingMethod();
                    var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;

                    return selectedMethod;
                }, this);

                return this;
            },
            getServicePointInformation: function(){
                var address = JSON.parse(window.sessionStorage.getItem('service-point-data'));

                return address;
            }
        });
    };
});