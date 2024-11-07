<?php

declare(strict_types=1);

namespace Gadget\Util;

/**
 * @template V
 */
class CollectionComparator
{
    /**
     * @param V $a
     * @param V $b
     * @return bool
     */
    public function equals(
        mixed $a,
        mixed $b
    ): bool {
        return $this->compare($a, $b) === 0;
    }


    /**
     * @param V $a
     * @param V $b
     * @return int
     */
    public function compare(
        mixed $a,
        mixed $b
    ): int {
        return ($a <=> $b);
    }


    /**
     * @param V $a
     * @param V $b
     * @return int
     */
    public function compareDesc(
        mixed $a,
        mixed $b
    ): int {
        return $this->compare($b, $a);
    }
}
