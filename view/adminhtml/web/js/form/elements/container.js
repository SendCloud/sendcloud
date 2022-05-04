define([
    'Magento_Ui/js/form/components/html'
], function (Component) {
'use strict';
    return Component.extend({
        /**
         * Show element.
         */
        show: function () {
            this.visible(true);
            return this;
        },

        /**
         * Hide element.
         */
        hide: function () {
            this.visible(false);
            return this;
        },
    });
});
