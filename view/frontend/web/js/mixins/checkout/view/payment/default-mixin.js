/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
], function () {
    'use strict';

    return function (Component) {
        return Component.extend({
            /**
             * After place order callback
             */
            afterPlaceOrder: function () {
                // Override this function and put after place order logic here
                window.sessionStorage.removeItem("service-point-data");
            }
        });
    }
});
