define([
], function () {
    'use strict';

    return function (Component) {
        return Component.extend({
            afterPlaceOrder: function () {
                // Override this function and put after place order logic here
                window.sessionStorage.removeItem("service-point-data");
            }
        });
    }
});
