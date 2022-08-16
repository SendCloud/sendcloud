define([
    'Magento_Ui/js/grid/provider'
], function (Provider) {
    'use strict';

    return Provider.extend({
        /**
         * Handles successful data reload.
         *
         * @param {Object} data - Retrieved data object.
         */
        onReload: function (data) {
            this.firstLoad = false;

            this.set('lastError', false);
            this.storage().clearRequests();
            this.setData(data)
                .trigger('reloaded');
        },

    });
});
