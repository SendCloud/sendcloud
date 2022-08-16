<?php

namespace SendCloud\SendCloud\CheckoutCore\SchemaProviders;

use SendCloud\SendCloud\CheckoutCore\Contracts\SchemaProviders\SchemaProvider;

class BaseDeliverySchemaProvider implements SchemaProvider
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
                'type' => 'string'
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
                'field' => 'carrier',
                'type' => 'complex',
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
                'field' => 'shipping_product',
                'type' => 'complex',
                'child' => [
                    [
                        'field' => 'code',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'name',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'lead_time_hours',
                        'nullable' => true,
                        'type' => 'int'
                    ],
                    [
                        'field' => 'lead_time_hours_override',
                        'nullable' => true,
                        'type' => 'int'
                    ],
                    [
                        'field' => 'selected_functionalities',
                        'type' => 'array'
                    ],
                    [
                        'field' => 'carrier_delivery_days',
                        'type' => 'complex',
                        'child' => [
                            [
                                'field' => 'monday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => [
                                    [
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ], [
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ],
                                ]
                            ],
                            [
                                'field' => 'tuesday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => [
                                    [
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ], [
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ],
                                ]
                            ],
                            [
                                'field' => 'wednesday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => [
                                    [
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ], [
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ],
                                ]
                            ],
                            [
                                'field' => 'thursday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => [
                                    [
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ], [
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ],
                                ]
                            ],
                            [
                                'field' => 'friday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => [
                                    [
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ], [
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ],
                                ]
                            ],
                            [
                                'field' => 'saturday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => [
                                    [
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ], [
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ],
                                ]
                            ],
                            [
                                'field' => 'sunday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => [
                                    [
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ], [
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ], [
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ],
                                ]
                            ]
                        ]
                    ],
                ]
            ],
            [
                'field' => 'parcel_handover_days',
                'type' => 'complex',
                'required' => false,
                'child' => [
                    [
                        'field' => 'monday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'tuesday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'wednesday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'thursday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'friday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'saturday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'sunday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ]
                ]
            ],
            [
                'field' => 'order_placement_days',
                'type' => 'complex',
                'required' => false,
                'child' => [
                    [
                        'field' => 'monday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'tuesday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'wednesday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'thursday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'friday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'saturday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ],
                    [
                        'field' => 'sunday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => [
                            [
                                'field' => 'enabled',
                                'type' => 'bool'
                            ],
                            [
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ],
                            [
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ],
                        ]
                    ]
                ]
            ],
            [
                'field' => 'holidays',
                'type' => 'collection',
                'empty' => true,
                'required' => false,
                'child' => [
                    [
                        'field' => 'frequency',
                        'type' => 'string',
                        'nullable' => true,
                    ],
                    [
                        'field' => 'from_date',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'recurring',
                        'type' => 'bool'
                    ],
                    [
                        'field' => 'title',
                        'type' => 'string'
                    ],
                    [
                        'field' => 'to_date',
                        'type' => 'string'
                    ],
                ]
            ],
        ];
    }
}
