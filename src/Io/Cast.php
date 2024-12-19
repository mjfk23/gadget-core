<?php

declare(strict_types=1);

namespace Gadget\Io;

final class Cast
{
    private const ERR_MSG = "Expected '%s', actual '%s'";
    private const BOOL_VALUES = ['1', 'O', 'T', 'X', 'Y'];


    /**
     * @param mixed $value
     * @return mixed[]
     */
    public static function toArray(mixed $value): array
    {
        if (is_string($value)) {
            $value = JSON::decode($value);
        }

        return match (true) {
            is_array($value) => $value,
            is_object($value) => get_object_vars($value),
            default => throw new \TypeError(sprintf(self::ERR_MSG, 'array', gettype($value)))
        };
    }


    /**
     * @param mixed $value
     * @return mixed[]|null
     */
    public static function toArrayOrNull(mixed $value): array|null
    {
        if (is_string($value)) {
            $value = JSON::decode($value);
        }

        return $value !== null ? self::toArray($value) : null;
    }


    /**
     * @param mixed $value
     * @return bool
     */
    public static function toBool(mixed $value): bool
    {
        return match (true) {
            is_bool($value) => $value,
            is_scalar($value) || $value instanceof \Stringable => in_array(
                strtoupper(substr(strval($value), 0, 1)),
                self::BOOL_VALUES,
                true
            ),
            default => throw new \TypeError(sprintf(self::ERR_MSG, 'boolean', gettype($value)))
        };
    }


    /**
     * @param mixed $value
     * @return bool|null
     */
    public static function toBoolOrNull(mixed $value): bool|null
    {
        return $value !== null ? self::toBool($value) : null;
    }


    /**
     * @param mixed $value
     * @return float
     */
    public static function toFloat(mixed $value): float
    {
        return match (true) {
            is_float($value) => $value,
            is_scalar($value) => floatval($value),
            $value instanceof \Stringable => floatval($value->__toString()),
            default => throw new \TypeError(sprintf(self::ERR_MSG, 'float', gettype($value)))
        };
    }


    /**
     * @param mixed $value
     * @return float|null
     */
    public static function toFloatOrNull(mixed $value): float|null
    {
        return $value !== null ? self::toFloat($value) : null;
    }


    /**
     * @param mixed $value
     * @return int
     */
    public static function toInt(mixed $value): int
    {
        return match (true) {
            is_int($value) => $value,
            is_scalar($value) => intval($value),
            $value instanceof \Stringable => intval($value->__toString()),
            default => throw new \TypeError(sprintf(self::ERR_MSG, 'integer', gettype($value)))
        };
    }


    /**
     * @param mixed $value
     * @return int|null
     */
    public static function toIntOrNull(mixed $value): int|null
    {
        return $value !== null ? self::toInt($value) : null;
    }


    /**
     * @param mixed $value
     * @return string
     */
    public static function toString(mixed $value): string
    {
        return match (true) {
            is_string($value) => $value,
            is_scalar($value) || $value instanceof \Stringable => strval($value),
            default => throw new \TypeError(sprintf(self::ERR_MSG, 'string', gettype($value)))
        };
    }


    /**
     * @param mixed $value
     * @return string|null
     */
    public static function toStringOrNull(mixed $value): string|null
    {
        return $value !== null ? self::toString($value) : null;
    }


    /**
     * @template TCastValue
     * @param mixed $values
     * @param (callable(mixed $value):TCastValue) $toValue
     * @return TCastValue[]
     */
    public static function toTypedArray(
        mixed $values,
        callable $toValue
    ): array {
        return array_map($toValue, array_values(self::toArray($values)));
    }


    /**
     * @template TCastValue
     * @param mixed $values
     * @param (callable(mixed $value):TCastValue) $toValue
     * @return TCastValue[]|null
     */
    public static function toTypedArrayOrNull(
        mixed $values,
        callable $toValue
    ): array|null {
        if (is_string($values)) {
            $values = JSON::decode($values);
        }

        return $values !== null ? self::toTypedArray($values, $toValue) : null;
    }


    /**
     * @template TCastObject of object
     * @param mixed $values
     * @param (callable(mixed $values):TCastObject) $factory
     * @return TCastObject
     */
    public static function toObject(
        mixed $values,
        callable $factory
    ): object {
        if (is_string($values)) {
            $values = JSON::decode($values);
        }

        return $factory($values);
    }


    /**
     * @template TCastObject of object
     * @param mixed $values
     * @param (callable(mixed $values):TCastObject) $factory
     * @return TCastObject|null
     */
    public static function toObjectOrNull(
        mixed $values,
        callable $factory
    ): object|null {
        if (is_string($values)) {
            $values = JSON::decode($values);
        }

        return $values !== null ? self::toObject($values, $factory) : null;
    }


    /**
     * @template TCastValue
     * @param mixed $values
     * @param (callable(mixed $value):TCastValue) $toValue
     * @param (callable(TCastValue $value):string) $key
     * @return array<string,TCastValue>
     */
    public static function toTypedMap(
        mixed $values,
        callable $toValue,
        callable $key
    ): array {
        return array_column(
            array_map(
                /**
                 * @param TCastValue $v
                 * @return array{TCastValue,string}
                 */
                fn(mixed $v): array => [$v, $key($v)],
                self::toTypedArray($values, $toValue)
            ),
            0,
            1
        );
    }


    /**
     * @template TCastValue
     * @param mixed $values
     * @param (callable(mixed $value):TCastValue) $toValue
     * @param (callable(TCastValue $value):string) $key
     * @return array<string,TCastValue>|null
     */
    public static function toTypedMapOrNull(
        mixed $values,
        callable $toValue,
        callable $key
    ): array|null {
        $values = self::toArrayOrNull($values);
        return $values !== null ? self::toTypedMap($values, $toValue, $key) : null;
    }


    /**
     * @template TCastValue
     * @param mixed $value
     * @param (callable(mixed $value):TCastValue) $toValue
     * @return TCastValue|null
     */
    public static function toValueOrNull(
        mixed $value,
        callable $toValue
    ): mixed {
        return $value !== null ? $toValue($value) : $value;
    }
}
