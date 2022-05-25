define([
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote',
    'underscore'
], function (wrapper, quote, _) {
    'use strict';

    return function (payloadExtender) {
        return wrapper.wrap(payloadExtender, function (originalAction, payload) {
            payload = originalAction(payload);

            if (quote.shippingMethod() && quote.shippingMethod()['method_code'] === 'sendcloud') {
                let servicePointAttributes = quote.getSendcloudServicePoint();
                if (servicePointAttributes && servicePointAttributes['sendcloud_service_point_id'] > 0) {
                    let sendCloudAttributes = {
                        sendcloud_service_point_id: servicePointAttributes['sendcloud_service_point_id'],
                        sendcloud_service_point_name: servicePointAttributes['sendcloud_service_point_name'],
                        sendcloud_service_point_street: servicePointAttributes['sendcloud_service_point_street'],
                        sendcloud_service_point_house_number: servicePointAttributes['sendcloud_service_point_house_number'],
                        sendcloud_service_point_zip_code: servicePointAttributes['sendcloud_service_point_zip_code'],
                        sendcloud_service_point_city: servicePointAttributes['sendcloud_service_point_city'],
                        sendcloud_service_point_country: servicePointAttributes['sendcloud_service_point_country'],
                        sendcloud_service_point_postnumber: servicePointAttributes['sendcloud_service_point_postnumber']
                    };
                    _.extend(payload['addressInformation']['extension_attributes'], sendCloudAttributes);
                }
            }

            return payload;
        });
    };
});