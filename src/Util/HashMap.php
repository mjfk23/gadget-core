<?php

declare(strict_types=1);

namespace Gadget\Util;

use Gadget\Io\JSON;

/**
 * @template V
 * @template-extends Map<V>
 */
class HashMap extends Map
{
    /**
     * @param V $value
     * @return string
     */
    public function hash(mixed $value): string
    {
        return hash('SHA256', sprintf(
            "%s::%s",
            is_object($value) ? $value::class : gettype($value),
            JSON::encode($value)
        ));
    }


    /**
     * @param string $key
     * @param V $value
     * @return V
     */
    public function setElement(
        string|int $key,
        mixed $value
    ): mixed {
        return parent::setElement($this->hash($value), $value);
    }
}
