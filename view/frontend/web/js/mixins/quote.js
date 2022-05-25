define([
    'ko',
], function (ko) {
    'use strict';

    return function (Component) {
        var servicePointData = ko.observable(window.checkoutConfig.quoteData.extension_attributes);

        Component.setSendcloudServicePoint =  function (extension_attributes){
            servicePointData(extension_attributes);
        };
        Component.getSendcloudServicePoint = function (){
            return servicePointData();
        };

        return Component;
    };
});