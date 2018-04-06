define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote'
], function ($, ko, Component, quote) {
    'use strict';
    var self = this;

    return Component.extend({
        defaults: {
            template: 'CreativeICT_SendCloud/checkout/shipping/servicePoint',
            scriptUrl: ''
        },
        initObservable: function () {
            this.selectedMethod = ko.computed(function() {
                var method = quote.shippingMethod();
                var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;

                return selectedMethod;
            }, this);
            var sendCloudScript = document.createElement('script');

            sendCloudScript.setAttribute('src', this.scriptUrl);
            document.head.appendChild(sendCloudScript);

            return this;
        },
        openSendCloudMap: function () {
            var zipCode = $('[name="postcode"]').val(),
                countryCode = $('[name="country_id"]').val();

            this.openServicePointPicker(zipCode, countryCode);
        },
        openServicePointPicker: function (zipCode, countryCode) {
            var config = {
                // API key is required, replace it below with your API key
                'apiKey': "f3348a202ce74a5497859d3a3d476511",
                // Country is required
                'country': countryCode.toLowerCase(),
                // Postal code is not required, although we recommend it
                'postalCode': zipCode,
                // Language is also not required. defaults to "en" - (available options en, fr, nl, de)
                'language': "nl",
                // you can filter service points by carriers as well.
                'carriers': null, // comma separated string (e.g. "postnl,bpost,dhl")
                // you can also pass a servicePointId if you want the map to be opened at a preselected service point
                'servicePointId': null // integer
            }

            sendcloud.servicePoints.open(
                config,
                function(servicePointObject) {
                    $('#servicePointTitle').html("<strong>Selected service point</strong>");
                    $('#servicePointName').html(servicePointObject.name);
                    $('#servicePointStreetAndHouseNumber').html(servicePointObject.street + " " + servicePointObject.house_number);
                    $('#servicePointZipCode').html(servicePointObject.postal_code);
                    $('#servicePointCity').html(servicePointObject.city);
                    $('#servicePointPhone').html(servicePointObject.phone);
                },
                function(errors) {
                    errors.forEach(function(error) {
                        console.log('Failure callback, reason: ' + error);
                    });
                }
            );
        }
    });

    ko.applyBindings();
});