<?php

namespace SendCloud\SendCloud\CheckoutCore\SchemaProviders;

use SendCloud\SendCloud\CheckoutCore\Contracts\SchemaProviders\SchemaProvider;

class CheckoutConfigurationSchemaProvider implements SchemaProvider
{
    public static function getSchema()
    {
        return array(
            array(
                'field' => 'checkout_configuration',
                'type' => 'complex',
                'child' => array(
                    array(
                        'field' => 'id',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'integration_id',
                        'type' => 'int'
                    ),
                    array(
                        'field' => 'version',
                        'type' => 'int'
                    ),
                    array(
                        'field' => 'updated_at',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'currency',
                        'type' => 'currency'
                    ),
                    array(
                        'field' => 'minimal_plugin_version',
                        'type' => 'string'
                    ),
                    array(
                        'field' => 'delivery_zones',
                        'type' => 'collection',
                        'empty' => false,
                        'child' => array(
                            array(
                                'field' => 'id',
                                'type' => 'string'
                            ),
                            array(
                                'field' => 'location',
                                'type' => 'complex',
                                'child' => array(
                                    array(
                                        'field' => 'country',
                                        'type' => 'complex',
                                        'child' => array(
                                            array(
                                                'field' => 'iso_2',
                                                'type' => 'string'
                                            ),
                                            array(
                                                'field' => 'name',
                                                'type' => 'string'
                                            ),
                                        )
                                    )
                                )
                            ),
                            array(
                                'field' => 'delivery_methods',
                                'type' => 'collection',
                                'empty' => false,
                                'schema_provider' => function ($deliveryMethod) {
                                    if (array_key_exists('delivery_method_type', $deliveryMethod) && $deliveryMethod['delivery_method_type'] === 'service_point_delivery') {
                                        return ServicePointDeliverySchemaProvider::getSchema();
                                    }

                                    return BaseDeliverySchemaProvider::getSchema();
                                }
                            ),
                        )
                    ),
                )
            )
        );
    }
}