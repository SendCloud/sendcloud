define([
    'underscore',
    'Magento_Checkout/js/model/quote'
], function (_, quote) {
    'use strict';

    return {
        savedAddress: '',
        shippingMethod: '',
        shippingCarrier: '',

        /**
         * Set saved data
         *
         * @param {object} address
         */
        register: function (address) {
            if (!address) {
                address = quote.shippingAddress();
            }
            this.savedAddress = address;
            this.shippingMethod = quote.shippingMethod().method_code;
            this.shippingCarrier = quote.shippingMethod().carrier_code;
        },

        /**
         * Compare current Shipping Data with saved and determines is need to save it
         *
         * @returns {boolean}
         */
        isHaveUnsavedShipping: function () {
            var methodData = quote.shippingMethod();
            if (!methodData) {
                return false;
            }
            if (!this.savedAddress) {
                return true;
            }

            return !this._compareObjectsData(quote.shippingAddress(), this.savedAddress)
                || this.shippingMethod !== methodData.method_code
                || this.shippingCarrier !== methodData.carrier_code;
        },

        /**
         * Is objects are equal
         *
         * @param {*} objA
         * @param {*} objB
         * @returns {boolean}
         * @private
         */
        _compareObjectsData: function (objA, objB) {
            // remove functions
            objA = _.pick(objA, function (value) {
                return !_.isFunction(value);
            });
            objB = _.pick(objB, function (value) {
                return !_.isFunction(value);
            });

            // objects for isEqual should not contain functions. Same for objects of their objects
            return _.isEqual(objA, objB);
        }
    };
});
