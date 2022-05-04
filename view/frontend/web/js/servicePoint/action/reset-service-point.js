define([
    'Magento_Checkout/js/model/quote',
], function (quote) {
    'use strict';
    return {
        /**
         * Reset service point data if address is changed
         *
         * @param countryCode
         * @param zipCode
         */
        resetServicePoint: function (countryCode, zipCode) {
            if(quote.getExtensionAttributes() &&
                (quote.getExtensionAttributes()['sendcloud_service_point_country'] !== countryCode ||
                    quote.getExtensionAttributes()['sendcloud_service_point_zip_code'] !== zipCode))
            {
                var extensionData = quote.getExtensionAttributes();
                extensionData.sendcloud_service_point_id = null;
                extensionData.sendcloud_service_point_name = null;
                extensionData.sendcloud_service_point_street = null;
                extensionData.sendcloud_service_point_house_number = null;
                extensionData.sendcloud_service_point_zip_code = null;
                extensionData.sendcloud_service_point_city = null;
                extensionData.sendcloud_service_point_country = null;
                extensionData.sendcloud_service_point_postnumber = null;

                quote.setExtensionAttributes(extensionData);
            }
        }
    };
});
