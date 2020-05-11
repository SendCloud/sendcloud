define([
    'jquery',
    'ko',
    'uiComponent',
    'Magento_Checkout/js/model/quote',
    'Magento_Customer/js/model/customer',
    'Magento_Checkout/js/action/set-shipping-information',
    'uiRegistry',
    'mage/translate',
    'https://embed.sendcloud.sc/spp/1.0.0/api.min.js'
], function ($, ko, Component, quote, customer, setShippingInformationAction, registry) {
    'use strict';
    var self = this,
        servicePointData = ko.observable(false);

    return Component.extend({
        defaults: {
            template: 'SendCloud_SendCloud/checkout/shipping/servicePoint',
            scriptUrl: ''
        },
        servicePointData: servicePointData,
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
            if (serviceObject) {
                this.servicePointButton = $.mage.__("Change service point");
                servicePointData(serviceObject);

                $('.button-service-point').text(this.servicePointButton);

                $('#servicePointName').html("<strong>" + serviceObject.name + "</strong>");
                $('#servicePointStreetAndHouseNumber').html(serviceObject.street + " " + serviceObject.house_number);
                $('#servicePointZipCode').html(serviceObject.postal_code);
                $('#servicePointCity').html(serviceObject.city);
                $('#servicePointPostnumber').html(serviceObject.postnumber);

                $('input[name="sendcloud_service_point_id"]').val(serviceObject.id);
                $('input[name="sendcloud_service_point_name"]').val(serviceObject.name);
                $('input[name="sendcloud_service_point_street"]').val(serviceObject.street);
                $('input[name="sendcloud_service_point_house_number"]').val(serviceObject.house_number);
                $('input[name="sendcloud_service_point_zip_code"]').val(serviceObject.postal_code);
                $('input[name="sendcloud_service_point_city"]').val(serviceObject.city);
                $('input[name="sendcloud_service_point_country"]').val(serviceObject.country);
                $('input[name="sendcloud_service_point_postnumber"]').val(serviceObject.postnumber);
            }
        },
        sessionData: function() {
            var serviceObject = JSON.parse(window.sessionStorage.getItem('service-point-data'));

            this.servicePoint(serviceObject);

            return serviceObject;
        },
        openSendCloudMap: function (e) {
            var zipCode = $('[name="postcode"]').val(),
                countryCode = $('[name="country_id"]').val();

            if (customer.isLoggedIn() && customer.getShippingAddressList()[0]) {
                zipCode = quote.shippingAddress().postcode;
                countryCode = quote.shippingAddress().countryId;
            }

            zipCode = zipCode.replace(' ', '');

            this.openServicePointPicker(zipCode, countryCode);
        },
        openServicePointPicker: function (zipCode, countryCode) {
            var self = this;
            var servicePointId = null;
            var postNumber = null;

            if (self.sessionData() && self.sessionData()['id']) {
                servicePointId = self.sessionData()[['id']];
            }

            if (self.sessionData() && self.sessionData()['postNumber']) {
                postNumber = self.sessionData()[['postNumber']];
            }

            var lang = document.documentElement.lang;
            if(!(lang == 'en' || lang == 'fr' || lang == 'nl' || lang == 'de')){
                lang = 'en'
            }

            var config = {
                // API key is required, replace it below with your API key
                'apiKey': sendcloud.getApiKey(),
                // Country is required
                'country': countryCode.toLowerCase(),
                // Postal code is not required, although we recommend it
                'postalCode': zipCode,
                // Language is also not required. defaults to "en" - (available options en, fr, nl, de)
                'language': lang,
                // you can filter service points by carriers as well.
                'carriers': null, // comma separated string (e.g. "postnl,bpost,dhl")
                // you can also pass a servicePointId if you want the map to be opened at a preselected service point
                'servicePointId': servicePointId, // integer,
                'postNumber': postNumber
            }

            sendcloud.servicePoints.open(
                config,
                function(servicePointObject, postNumber) {
                    var sessionData = {
                            id: servicePointObject.id,
                            name: servicePointObject.name,
                            street: servicePointObject.street,
                            house_number: servicePointObject.house_number,
                            postal_code: servicePointObject.postal_code,
                            city: servicePointObject.city,
                            country: servicePointObject.country,
                            formatted_opening_times: servicePointObject.formatted_opening_times,
                            postnumber: postNumber
                        };
                    window.checkoutConfig.quoteData

                    self.servicePoint(sessionData);

                    window.sessionStorage.setItem("service-point-data", JSON.stringify(sessionData));

                    var shipping = registry.get('checkout.steps.shipping-step.shippingAddress'),
                        result;

                    result = shipping.validateShippingInformation();
                    if (result) {
                        setShippingInformationAction();
                    }

                },
                function(errors) {
                    errors.forEach(function(error) {
                        console.log('Failure callback, reason: ' + error);
                    });
                }
            );
        }
    });
});
