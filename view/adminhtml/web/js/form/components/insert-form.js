define([
    'Magento_Ui/js/form/components/insert-form',
    'jquery',
], function (Insert, $) {
    'use strict';

    return Insert.extend({
        /**
         * Insert actions in toolbar.
         *
         * @param {String} actions
         */
        renderActions: function (actions) {
            //empty because we don't need action toolbar here
        },
        render: function (params) {
            if(params && params.hasOwnProperty('entity_id') && params.hasOwnProperty('integration_id')){
                var self = this,
                    request;

                if (this.isRendered) {
                    return this;
                }

                self.previousParams = params || {};

                $.async({
                    component: this.name,
                    ctx: '.' + this.contentSelector
                }, function (el) {
                    self.contentEl = $(el);
                    self.startRender = true;
                    params = _.extend({}, self.params, params || {});
                    request = self.requestData(params, self.renderSettings);
                    request
                        .done(self.onRender)
                        .fail(self.onError);
                });

                return this;
            }
        },

    });
});
