define(
    [],
    function () {
        'use strict';
        return {
            getRules: function () {
                return {
                    'sendcloud_service_point_id': {
                        'required': true
                    },
                    'sendcloud_service_point_name': {
                        'required': true
                    }
                }
            }
        }
    }
)