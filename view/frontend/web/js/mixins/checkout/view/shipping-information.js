define([
    'jquery'
], function($){
    'use strict';
    return function(c){
        //if targetModule is a uiClass based objec
        var self = this;
        return c.extend({
            defaults: {
                template: 'SendCloud_SendCloud/shipping-information'
            },
            getServicePointInformation: function(){
                var address = JSON.parse(window.sessionStorage.getItem('service-point-data'));

                return address;
            }
        });
    };
});
