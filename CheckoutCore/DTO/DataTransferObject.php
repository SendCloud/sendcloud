<?php

namespace SendCloud\SendCloud\CheckoutCore\DTO;

use RuntimeException;

/**
 * Class DataTransferObject
 *
 * @package SendCloud\SendCloud\CheckoutCore\DTO
 */
abstract class DataTransferObject
{
    /**
     * Provides array representation of a dto.
     *
     * @return array Array representation.
     */
    public function toArray()
    {
        return [];
    }

    /**
     * Transforms dto collection to array collection.
     *
     * @param DataTransferObject[] $dtos Collection of dtos.
     * @return array Array collection.
     */
    public static function toArrayBatch(array $dtos)
    {
        $result = [];
        foreach ($dtos as $index => $dto) {
            $result[$index] = $dto !== null ? $dto->toArray() : null;
        }

        return $result;
    }

    /**
     * Instantiates data transfer object from an array.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return DataTransferObject DTO instance.
     */
    public static function fromArray(array $rawData)
    {
        return static::instantiate($rawData);
    }

    /**
     * Instantiates collection of dtos from batch of raw data where each item is a valid dto.
     *
     * @param array $batch Batch of raw data.
     * @return DataTransferObject[] DTO collection.
     */
    public static function fromArrayBatch(array $batch)
    {
        $result = [];
        foreach ($batch as $index => $item) {
            $result[$index] = $item !== null ? static::fromArray($item) : null;
        }

        return $result;
    }

    /**
     * Factory template method used to instantiate data transfer object from an array of data.
     *
     * @param array $rawData Raw data used for instantiation.
     *
     * @return DataTransferObject
     *
     * @noinspection PhpDocSignatureInspection
     * @noinspection PhpUnusedParameterInspection
     */
    protected static function instantiate(array $rawData)
    {
        throw new RuntimeException('Not Implemented.');
    }

    /**
     * Provides value of a raw data variable identified with key.
     *
     * @param array $rawData Raw data.
     * @param string $key Identifying key.
     * @param mixed $default Default value returned if the variable is not found.
     *
     * @return mixed
     */
    protected static function getValue(array $rawData, $key, $default = '')
    {
        return isset($rawData[$key]) ? $rawData[$key] : $default;
    }
}
