<?php

namespace SendCloud\SendCloud\CheckoutCore\SchemaProviders;

use SendCloud\SendCloud\CheckoutCore\Contracts\SchemaProviders\SchemaProvider;

class CheckoutConfigurationSchemaProvider implements SchemaProvider
{
    public static function getSchema()
    {
        return [
            [
                'field' => 'checkout_configuration',
                'type' => 'complex',
                'child' => [
                    [
                        'field' => 'id',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'integration_id',
                        'type' => 'int'
                    ],
                    [
                        'field' => 'version',
                        'type' => 'int'
                    ],
                    [
                        'field' => 'updated_at',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'currency',
                        'type' => 'currency'
                    ],
                    [
                        'field' => 'minimal_plugin_version',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'delivery_zones',
                        'type' => 'collection',
                        'empty' => false,
                        'child' => [
                            [
                                'field' => 'id',
                                'type' => 'string'
                            ],
                            [
                                'field' => 'location',
                                'type' => 'complex',
                                'child' => [
                                    [
                                        'field' => 'country',
                                        'type' => 'complex',
                                        'child' => [
                                            [
                                                'field' => 'iso_2',
                                                'type' => 'string'
                                            ],
                                            [
                                                'field' => 'name',
                                                'type' => 'string'
                                            ],
                                        ]
                                    ]
                                ]
                            ],
                            [
                                'field' => 'delivery_methods',
                                'type' => 'collection',
                                'empty' => false,
                                'schema_provider' => function ($deliveryMethod) {
                                    if (array_key_exists('delivery_method_type', $deliveryMethod) && $deliveryMethod['delivery_method_type'] === 'service_point_delivery') {
                                        return ServicePointDeliverySchemaProvider::getSchema();
                                    }

                                    return BaseDeliverySchemaProvider::getSchema();
                                }
                            ],
                        ]
                    ],
                ]
            ]
        ];
    }
}
