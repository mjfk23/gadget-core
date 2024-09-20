<?php

declare(strict_types=1);

namespace Gadget\Util;

/**
 * @template TElement
 * @extends Collection<TElement>
 * @implements \IteratorAggregate<string,TElement>
 */
class Map extends Collection implements \IteratorAggregate
{
    /**
     * @param TElement[] $elements
     * @param (callable(TElement $value): string)|null $key
     */
    public function __construct(
        array $elements = [],
        callable|null $key = null
    ) {
        parent::__construct();
        if (is_callable($key)) {
            foreach ($elements as $element) {
                $this->set($key($element), $element);
            }
        } else {
            foreach ($elements as $k => $element) {
                $this->set((string) $k, $element);
            }
        }
    }


    /**
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->elements[$key]);
    }


    /**
     * @param string $key
     * @return TElement|null
     */
    public function get(string $key): mixed
    {
        return $this->elements[$key] ?? null;
    }


    /**
     * @param string $key
     * @param TElement $element
     * @return void
     */
    public function set(
        string $key,
        mixed $element
    ): void {
        $this->elements[$key] = $element;
    }


    /**
     * @param string $key
     * @return TElement|null
     */
    public function remove(string $key): mixed
    {
        $element = $this->get($key);
        unset($this->elements[$key]);
        return $element;
    }


    /**
     * @return \Traversable<string,TElement>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->elements;
    }
}
