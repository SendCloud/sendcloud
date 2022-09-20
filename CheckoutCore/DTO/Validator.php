<?php

namespace SendCloud\SendCloud\CheckoutCore\DTO;

use SendCloud\SendCloud\CheckoutCore\Exceptions\DTO\DTOValidationException;

/**
 * Class Validator
 *
 * @package SendCloud\SendCloud\CheckoutCore\DTO
 */
class Validator
{
    /**
     * Validates data against schema.
     *
     * @param array $schema
     * @param array $data
     * @param $defaultCurrencyCode
     * @throws DTOValidationException
     */
    public static function validate(array $schema, array $data, $defaultCurrencyCode)
    {
        $errors = array();
        static::doValidate($schema, $data, $errors, array(), $defaultCurrencyCode);
        if (!empty($errors)) {
            throw new DTOValidationException($errors);
        }
    }

    /**
     * Performs validation.
     *
     * @param array $schema
     * @param array $data
     * @param array $errors
     * @param array $path
     */
    private static function doValidate(array $schema, array $data, array &$errors, array $path, $defaultCurrencyCode)
    {
        foreach ($schema as $field) {
            // Validate field's existence.
            if (!array_key_exists($field['field'], $data)) {
                if (!(array_key_exists('required', $field) && $field['required'] === false)) {
                    $errors[] = array(
                        'path' => static::formatPath($path, $field['field']),
                        'message' => 'Field is required.',
                    );
                }

                continue;
            }

            // Validate nullable field.
            if ($data[$field['field']] === null) {
                if (empty($field['nullable'])) {
                    $errors[] = array(
                        'path' => static::formatPath($path, $field['field']),
                        'message' => 'Field cannot be null.',
                    );
                }

                continue;
            }

            if ($field['type'] === 'currency' && $data[$field['field']] !== $defaultCurrencyCode) {
                $errors[] = array(
                    'path' => static::formatPath($path, $field['field']),
                    'context' => array(
                        'default_currency' => $defaultCurrencyCode
                    ),
                    'code' => 'currency_mismatch',
                    'message' => 'Configured currency does not match the default currency.',
                );
            }

            // Validate non-scalar fields.
            if ($field['type'] === 'complex') {
                if (array_key_exists('schema_provider', $field)) {
                    $child = call_user_func($field['schema_provider'], $data[$field['field']]);
                    self::doValidate($child, $data[$field['field']], $errors, array_merge($path, array($field['field'])), $defaultCurrencyCode);
                } else {
                    self::doValidate($field['child'], $data[$field['field']], $errors, array_merge($path, array($field['field'])), $defaultCurrencyCode);
                }
            } elseif ($field['type'] === 'collection') {
                if (empty($data[$field['field']]) && !$field['empty']) {
                    $errors[] = array(
                        'path' => static::formatPath($path, $field['field']),
                        'message' => 'Collection is empty',
                    );

                    continue;
                }

                foreach ($data[$field['field']] as $index => $item) {
                    if ($item === null) {
                        if (empty($field['contains_null'])) {
                            $errors[] = array(
                                'path' => static::formatPath($path, $field['field']),
                                'message' => 'Collection cannot contain null values.',
                            );
                        }

                        continue;
                    }

                    if (array_key_exists('schema_provider', $field)) {
                        $child = call_user_func($field['schema_provider'], $item);
                        self::doValidate($child, $item, $errors, array_merge($path, array($field['field'], $index)), $defaultCurrencyCode);
                    } else {
                        self::doValidate($field['child'], $item, $errors, array_merge($path, array($field['field'], $index)), $defaultCurrencyCode);
                    }
                }
            }
        }
    }

    /**
     * Formats path
     *
     * @param array $parent
     * @param string $field
     * @return array
     */
    private static function formatPath($parent, $field)
    {
        $parent[] = $field;

        return $parent;
    }
}