<?php

declare(strict_types=1);

namespace Gadget\Store;

/**
 * @template TStoreElement of object
 * @extends \IteratorAggregate<string,TStoreElement>
 */
interface ObjectStoreInterface extends \IteratorAggregate
{
    /**
     * @return bool
     */
    public function load(): bool;


    /**
     * @param string|TStoreElement $keyOrElement
     * @return string
     */
    public function key(string|object $keyOrElement): string;


    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool;


    /**
     * @param string $key
     * @return TStoreElement|null
     */
    public function get(string $key): object|null;


    /**
     * @param TStoreElement $element
     * @return bool
     */
    public function save(object $element): bool;


    /**
     * @param string|TStoreElement $keyOrElement
     * @return bool
     */
    public function delete(string|object $keyOrElement): bool;


    /**
     * @return bool
     */
    public function commit(): bool;


    /**
     * @return \Traversable<string,TStoreElement>
     */
    public function getIterator(): \Traversable;
}
