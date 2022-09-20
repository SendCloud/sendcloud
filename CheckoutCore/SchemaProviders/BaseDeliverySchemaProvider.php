<?php

namespace SendCloud\SendCloud\CheckoutCore\SchemaProviders;

use SendCloud\SendCloud\CheckoutCore\Contracts\SchemaProviders\SchemaProvider;

class BaseDeliverySchemaProvider implements SchemaProvider
{
    public static function getSchema()
    {
        return array(
            array(
                'field' => 'id',
                'type' => 'string'
            ),
            array(
                'field' => 'delivery_method_type',
                'type' => 'string'
            ),
            array(
                'field' => 'external_title',
                'type' => 'string'
            ),
            array(
                'field' => 'internal_title',
                'type' => 'string'
            ),
            array(
                'field' => 'sender_address_id',
                'type' => 'int'
            ),
            array(
                'field' => 'time_zone_name',
                'type' => 'string'
            ),
            array(
                'field' => 'show_carrier_information_in_checkout',
                'type' => 'bool'
            ),
            array(
                'field' => 'shipping_rate_data',
                'type' => 'complex',
                'child' => array(
                    array(
                        'field' => 'enabled',
                        'type' => 'bool',
                        'required' => false,
                    ),
                    array(
                        'field' => 'currency',
                        'type' => 'currency',
                        'required' => false,
                    ),
                    array(
                        'field' => 'free_shipping',
                        'type' => 'complex',
                        'required' => false,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool',
                            ),
                            array(
                                'field' => 'from_amount',
                                'type' => 'string',
                            ),
                        ),
                    ),
                    array(
                        'field' => 'shipping_rates',
                        'type' => 'collection',
                        'required' => false,
						'empty' => true,
                        'child' => array(
                            array(
                                'field' => 'rate',
                                'type' => 'string',
                            ),
                            array(
                                'field' => 'enabled',
                                'type' => 'bool',
                            ),
                            array(
                                'field' => 'is_default',
                                'type' => 'bool',
                            ),
                            array(
                                'field' => 'max_weight',
                                'type' => 'int',
                                'required' => false,
                            ),
                            array(
                                'field' => 'min_weight',
                                'type' => 'int',
                                'required' => false,
                            )
                        ),
                    ),
                ),
            ),
            array(
                'field' => 'carrier',
                'type' => 'complex',
                'child' => array(
                    array(
                        'field' => 'name',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'code',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'logo_url',
                        'type' => 'string'
                    ),
                )
            ),
            array(
                'field' => 'shipping_product',
                'type' => 'complex',
                'child' => array(
                    array(
                        'field' => 'code',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'name',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'lead_time_hours',
                        'nullable' => true,
                        'type' => 'int'
                    ),
                    array(
                        'field' => 'lead_time_hours_override',
                        'nullable' => true,
                        'type' => 'int'
                    ),
                    array(
                        'field' => 'selected_functionalities',
                        'type' => 'array'
                    ),
                    array(
                        'field' => 'carrier_delivery_days',
                        'type' => 'complex',
                        'child' => array(
                            array(
                                'field' => 'monday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => array(
                                    array(
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ), array(
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ),
                                )
                            ),
                            array(
                                'field' => 'tuesday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => array(
                                    array(
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ), array(
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ),
                                )
                            ),
                            array(
                                'field' => 'wednesday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => array(
                                    array(
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ), array(
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ),
                                )
                            ),
                            array(
                                'field' => 'thursday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => array(
                                    array(
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ), array(
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ),
                                )
                            ),
                            array(
                                'field' => 'friday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => array(
                                    array(
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ), array(
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ),
                                )
                            ),
                            array(
                                'field' => 'saturday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => array(
                                    array(
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ), array(
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ),
                                )
                            ),
                            array(
                                'field' => 'sunday',
                                'type' => 'complex',
                                'nullable' => true,
                                'child' => array(
                                    array(
                                        'field' => 'enabled',
                                        'type' => 'bool',
                                    ), array(
                                        'field' => 'start_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'start_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_hours',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ), array(
                                        'field' => 'end_time_minutes',
                                        'type' => 'int',
                                        'nullable' => true,
                                    ),
                                )
                            )
                        )
                    ),
                )
            ),
            array(
                'field' => 'parcel_handover_days',
                'type' => 'complex',
                'required' => false,
                'child' => array(
                    array(
                        'field' => 'monday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'tuesday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'wednesday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'thursday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'friday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'saturday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'sunday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    )
                )
            ),
            array(
                'field' => 'order_placement_days',
                'type' => 'complex',
                'required' => false,
                'child' => array(
                    array(
                        'field' => 'monday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'tuesday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'wednesday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'thursday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'friday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'saturday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    ),
                    array(
                        'field' => 'sunday',
                        'type' => 'complex',
                        'nullable' => true,
                        'child' => array(
                            array(
                                'field' => 'enabled',
                                'type' => 'bool'
                            ),
                            array(
                                'field' => 'cut_off_time_hours',
                                'type' => 'int'
                            ),
                            array(
                                'field' => 'cut_off_time_minutes',
                                'type' => 'int'
                            ),
                        )
                    )
                )
            ),
            array(
                'field' => 'holidays',
                'type' => 'collection',
                'empty' => true,
                'required' => false,
                'child' => array(
                    array(
                        'field' => 'frequency',
                        'type' => 'string',
                        'nullable' => true,
                    ),
                    array(
                        'field' => 'from_date',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'recurring',
                        'type' => 'bool'
                    ),
                    array(
                        'field' => 'title',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'to_date',
                        'type' => 'string'
                    ),
                )
            ),
        );
    }
}