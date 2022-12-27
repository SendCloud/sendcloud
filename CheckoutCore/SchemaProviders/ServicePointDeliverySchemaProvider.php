<?php

namespace SendCloud\SendCloud\CheckoutCore\SchemaProviders;

use SendCloud\SendCloud\CheckoutCore\Contracts\SchemaProviders\SchemaProvider;

class ServicePointDeliverySchemaProvider implements SchemaProvider
{
    public static function getSchema()
    {
        return [
            [
                'field' => 'id',
                'type' => 'string'
            ],
            [
                'field' => 'delivery_method_type',
                'type' => 'string'
            ],
            [
                'field' => 'external_title',
                'type' => 'string'
            ],
            [
                'field' => 'internal_title',
                'type' => 'string',
                'nullable' => true,
            ],
            [
                'field' => 'description',
                'type' => 'string',
                'nullable' => true,
            ],
            [
                'field' => 'sender_address_id',
                'type' => 'int'
            ],
            [
                'field' => 'time_zone_name',
                'type' => 'string'
            ],
            [
                'field' => 'show_carrier_information_in_checkout',
                'type' => 'bool'
            ],
            [
                'field' => 'carriers',
                'type' => 'collection',
                'child' => [
                    [
                        'field' => 'name',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'code',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'logo_url',
                        'type' => 'string'
                    ],
                ]
            ],
            [
                'field' => 'shipping_rate_data',
                'type' => 'complex',
                'child' => [
                    [
                        'field' => 'enabled',
                        'type' => 'bool',
                        'required' => false,
                    ],
                    [
                        'field' => 'currency',
                        'type' => 'currency',
                        'required' => false,
                    ],
                    [
                        'field' => 'free_shipping',
                        'type' => 'complex',
                        'required' => false,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool',
                            ],
                            [
                                'field' => 'from_amount',
                                'type' => 'string',
                            ],
                        ],
                    ],
                    [
                        'field' => 'shipping_rates',
                        'type' => 'collection',
                        'required' => false,
                        'empty' => true,
                        'child' => [
                            [
                                'field' => 'rate',
                                'type' => 'string',
                            ],
                            [
                                'field' => 'enabled',
                                'type' => 'bool',
                            ],
                            [
                                'field' => 'is_default',
                                'type' => 'bool',
                                'required' => false,
                            ],
                            [
                                'field' => 'max_weight',
                                'type' => 'int',
                                'required' => false,
                            ],
                            [
                                'field' => 'min_weight',
                                'type' => 'int',
                                'required' => false,
                            ]
                        ],
                    ],
                ],
            ],
            [
                'field' => 'service_point_data',
                'type' => 'complex',
                'child' => [
                    [
                        'field' => 'api_key',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'country_iso_2',
                        'type' => 'string'
                    ]
                ]
            ]
        ];
    }
}
