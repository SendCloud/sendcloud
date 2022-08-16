define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'SendCloud_SendCloud/js/servicePoint/action/reset-service-point',
    'SendCloud_SendCloud/js/checkout/action/reset-service-point'
], function ($, wrapper, quote, servicePoint, checkout) {
    'use strict';
    var lastKnownShippingAddressZip,
        lastKnownShippingAddressCountry;

    return function (target) {
        return wrapper.wrap(target, function (originalAction, data) {
            originalAction(data);

            let zipCode = quote.shippingAddress() ? quote.shippingAddress().postcode : null;
            let countryCode = quote.shippingAddress() ? quote.shippingAddress().countryId : null;
            if ((lastKnownShippingAddressCountry === countryCode) && (lastKnownShippingAddressZip === zipCode)) {
                return;
            }

            lastKnownShippingAddressCountry = countryCode;
            lastKnownShippingAddressZip = zipCode;

            checkout.resetServicePoint(countryCode, zipCode);
            servicePoint.resetServicePoint(countryCode, zipCode);

        });
    };
});
