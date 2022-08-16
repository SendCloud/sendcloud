define([
    'Magento_Checkout/js/model/quote',
    'jquery',
    'underscore'
], function (quote, $, _) {
    'use strict';
    return {
        /**
         * Add extension attributes
         *
         * @param payload
         * @returns {*}
         */
        extendData: function (payload) {
            if (quote.shippingMethod() && quote.shippingMethod()['carrier_code'] === 'sendcloud_checkout') {
                let extensionAttributes = quote.getExtensionAttributes();
                let checkoutExtAttr = quote.getCheckoutExtensionAttributes();

                if (extensionAttributes && quote.getCheckoutExtensionAttributes()) {
                    let sendCloudAttributes = {
                        sendcloud_service_point_id: extensionAttributes['sendcloud_service_point_id'],
                        sendcloud_service_point_name: extensionAttributes['sendcloud_service_point_name'],
                        sendcloud_service_point_street: extensionAttributes['sendcloud_service_point_street'],
                        sendcloud_service_point_house_number: extensionAttributes['sendcloud_service_point_house_number'],
                        sendcloud_service_point_zip_code: extensionAttributes['sendcloud_service_point_zip_code'],
                        sendcloud_service_point_city: extensionAttributes['sendcloud_service_point_city'],
                        sendcloud_service_point_country: extensionAttributes['sendcloud_service_point_country'],
                        sendcloud_service_point_postnumber: extensionAttributes['sendcloud_service_point_postnumber'],
                        sendcloud_checkout_data: JSON.stringify(quote.getCheckoutExtensionAttributes())
                    };

                    _.extend(payload['addressInformation']['extension_attributes'], sendCloudAttributes);

                }
            }

            return payload;
        }
    };
});
