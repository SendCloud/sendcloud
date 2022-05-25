define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
], function ($, wrapper, quote) {
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

            if(!quote.getSendcloudServicePoint() ||
                quote.getSendcloudServicePoint()['sendcloud_service_point_country'] !== countryCode ||
                quote.getSendcloudServicePoint()['sendcloud_service_point_zip_code'] !== zipCode)
            {
                quote.setSendcloudServicePoint(null);
            }
        });
    };
});
