<?php

namespace SendCloud\SendCloud\CheckoutCore\SchemaProviders;

use SendCloud\SendCloud\CheckoutCore\Contracts\SchemaProviders\SchemaProvider;

class ServicePointDeliverySchemaProvider implements SchemaProvider
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
                'field' => 'carriers',
                'type' => 'collection',
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
                        ),
                    ),
                ),
            ),
            array(
                'field' => 'service_point_data',
                'type' => 'complex',
                'child' => array(
                    array(
                        'field' => 'api_key',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'country_iso_2',
                        'type' => 'string'
                    )
                )
            )
        );
    }
}