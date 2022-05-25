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
    var self = this;

    return Component.extend({
        defaults: {
            template: 'SendCloud_SendCloud/checkout/shipping/servicePoint',
            scriptUrl: ''
        },
        servicePointButton: ko.observable($.mage.__("Select service point")),
        quote: quote,
        initObservable: function () {
            this.selectedMethod = ko.computed(function() {
                var method = quote.shippingMethod();
                var selectedMethod = method != null ? method.carrier_code + '_' + method.method_code : null;
                this.servicePoint(quote.getSendcloudServicePoint())
                return selectedMethod;
            }, this);

            this.isServicePointSelected = ko.computed(function() {
                return quote.getSendcloudServicePoint() && quote.getSendcloudServicePoint().sendcloud_service_point_id;
            }, this);

            var sendCloudScript = document.createElement('script');

            sendCloudScript.setAttribute('src', this.scriptUrl);
            document.head.appendChild(sendCloudScript);

            return this;
        },
        servicePoint: function (serviceObject) {
            if (!serviceObject || !serviceObject.sendcloud_service_point_id) {
                this.servicePointButton($.mage.__("Select service point"));
            }else{
                this.servicePointButton($.mage.__("Change service point"));
            }
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

            if (quote.getSendcloudServicePoint() && quote.getSendcloudServicePoint()['sendcloud_service_point_id']) {
                servicePointId = quote.getSendcloudServicePoint()['sendcloud_service_point_id'];
            }

            if (quote.getSendcloudServicePoint() && quote.getSendcloudServicePoint()['sendcloud_service_point_postnumber']) {
                postNumber = quote.getSendcloudServicePoint()['sendcloud_service_point_postnumber'];
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
                    var servicePoinData = {
                        sendcloud_service_point_id: servicePointObject.id,
                        sendcloud_service_point_name: servicePointObject.name,
                        sendcloud_service_point_street: servicePointObject.street,
                        sendcloud_service_point_house_number: servicePointObject.house_number,
                        sendcloud_service_point_zip_code: servicePointObject.postal_code,
                        sendcloud_service_point_city: servicePointObject.city,
                        sendcloud_service_point_country: servicePointObject.country,
                        sendcloud_service_point_postnumber: postNumber
                    };

                    self.servicePoint(servicePoinData);

                    quote.setSendcloudServicePoint(servicePoinData);
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
