<?php

declare(strict_types=1);

namespace Gadget\Io;

final class JSON
{
    /**
     * Decodes a JSON string
     *
     * @param string $json The json string being decoded.
     * @param int $flags Bitmask of `JSON_*` constants.
     * @param int<1,2147483647> $depth Set the maximum depth. Must be greater than zero.
     * @return mixed the value encoded in json in appropriate PHP type.
     */
    public static function decode(
        string $json,
        int $flags = 0,
        int $depth = 512
    ): mixed {
        return json_decode(
            $json,
            true,
            $depth,
            $flags | JSON_THROW_ON_ERROR
        );
    }


    /**
     * Returns the JSON representation of a value
     *
     * @param mixed $value The value being encoded. Can be any type except a resource.
     * @param int $flags Bitmask of `JSON_*` constants.
     * @param int<1,2147483647> $depth Set the maximum depth. Must be greater than zero.
     * @return string a JSON-encoded string
     * @see https://php.net/manual/en/function.json-encode.php
     */
    public static function encode(
        mixed $value,
        int $flags = 0,
        int $depth = 512
    ): string {
        return json_encode(
            $value,
            $flags | JSON_THROW_ON_ERROR,
            $depth
        );
    }
}
