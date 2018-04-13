define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'mage/translate'
], function ($, ko, Component, quote) {
    'use strict';
    var self = this;

    return Component.extend({
        defaults: {
            template: 'CreativeICT_SendCloud/checkout/shipping/servicePoint',
            scriptUrl: ''
        },
        servicePointData: ko.observable(),
        servicePointButton: ko.observable($.mage.__("Select service point")),
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
        servicePoint: function (serviceObject) {
            var openingTimes = '';
            if (serviceObject) {
                var days = [$.mage.__("Mon"), $.mage.__("Tue"), $.mage.__("Wed"), $.mage.__("Thu"), $.mage.__("Fri"), $.mage.__("Sat"), $.mage.__("Sun")];

                this.servicePointButton = $.mage.__("Change service point");

                $('#servicePointName').html("<strong>" + serviceObject.name + "</strong>");
                $('#servicePointStreetAndHouseNumber').html(serviceObject.street + " " + serviceObject.house_number);
                $('#servicePointZipCode').html(serviceObject.postal_code);
                $('#servicePointCity').html(serviceObject.city);

                openingTimes = serviceObject.formatted_opening_times;

                $('#servicePointOpeningTimes ul').html('');

                $.each(openingTimes, function (key, value) {
                    if (value.length > 0) {
                        $('#servicePointOpeningTimes ul').append('<li>' + days[key] + ' <span>' + value + '</span></li>');
                    }
                });

                $('input[name="sendcloud_service_point_id"]').val(serviceObject.id);
                $('input[name="sendcloud_service_point_name"]').val(serviceObject.name);
                $('input[name="sendcloud_service_point_street"]').val(serviceObject.street);
                $('input[name="sendcloud_service_point_house_number"]').val(serviceObject.house_number);
                $('input[name="sendcloud_service_point_zip_code"]').val(serviceObject.postal_code);
                $('input[name="sendcloud_service_point_city"]').val(serviceObject.city);
                $('input[name="sendcloud_service_point_country"]').val(serviceObject.country);
            }
        },
        sessionData: function() {
            var serviceObject = JSON.parse(window.sessionStorage.getItem('service-point-data'));

            this.servicePoint(serviceObject);
        },
        openSendCloudMap: function (e) {
            var zipCode = $('[name="postcode"]').val(),
                countryCode = $('[name="country_id"]').val();

            zipCode = zipCode.replace(' ', '');

            this.openServicePointPicker(zipCode, countryCode);
        },
        openServicePointPicker: function (zipCode, countryCode) {
            var self = this;
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
                    var sessionData = {
                            id: servicePointObject.id,
                            name: servicePointObject.name,
                            street: servicePointObject.street,
                            house_number: servicePointObject.house_number,
                            postal_code: servicePointObject.postal_code,
                            city: servicePointObject.city,
                            country: servicePointObject.country,
                            formatted_opening_times: servicePointObject.formatted_opening_times
                        };

                    self.servicePoint(servicePointObject);

                    window.sessionStorage.setItem("service-point-data", JSON.stringify(sessionData));
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