define([
    'Magento_Checkout/js/model/quote',
], function (quote) {
    'use strict';
    return {
        /**
         * Validate service point
         * 
         * @returns {boolean}
         */
        validateServicePoint: function () {
            if (quote.shippingMethod().method_code === 'sendcloud' &&
                (!quote.getExtensionAttributes() || !quote.getExtensionAttributes().sendcloud_service_point_id)) {
                return false;
            }
            return true;
        }
    };
});
