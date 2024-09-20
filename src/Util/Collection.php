<?php

declare(strict_types=1);

namespace Gadget\Util;

/**
 * @template TElement
 * @implements \IteratorAggregate<string|int,TElement>
 */
class Collection implements \IteratorAggregate, \Countable, \JsonSerializable
{
    /**
     * @param TElement[] $elements
     */
    public function __construct(protected array $elements = [])
    {
    }


    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return count($this->elements);
    }


    /**
     * @param TElement $element
     * @param (callable(TElement $a, TElement $b): bool)|null $equals
     * @return bool
     */
    public function contains(
        mixed $element,
        callable|null $equals = null
    ): bool {
        $equals ??= fn(mixed $a, mixed $b): bool => ($a <=> $b) === 0;
        return count(array_filter(
            $this->elements,
            fn ($e) => $equals($element, $e) === true
        )) > 0;
    }


    /**
     * @return bool
     */
    public function empty(): bool
    {
        return $this->count() < 1;
    }


    /**
     * @return void
     */
    public function clear(): void
    {
        $this->elements = [];
    }


    /**
     * @return \Traversable<string|int,TElement>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->elements;
    }


    /**
     * @return TElement[]
     */
    public function toArray(): array
    {
        return iterator_to_array($this->getIterator());
    }


    /**
     * @return (string|int)[]
     */
    public function keys(): mixed
    {
        return array_keys($this->toArray());
    }


    /**
     * @return TElement[]
     */
    public function values(): mixed
    {
        return array_values($this->toArray());
    }


    /**
     * @param int $order
     * @param int|(callable(mixed $a, mixed $b): int) $type
     * @return void
     */
    public function sort(
        int $order = SORT_ASC,
        int|callable $type = SORT_REGULAR
    ): void {
        if (is_callable($type)) {
            usort(
                $this->elements,
                $order === SORT_DESC
                    ? fn(mixed $a, mixed $b): int => $type($b, $a)
                    : $type
            );
        } elseif ($order === SORT_DESC) {
            rsort($this->elements, $type);
        } else {
            sort($this->elements, $type);
        }
    }


    /**
     * @param int $order
     * @param int|(callable(mixed $a, mixed $b): int) $type
     * @return void
     */
    public function keySort(
        int $order = SORT_ASC,
        int|callable $type = SORT_REGULAR
    ): void {
        if (is_callable($type)) {
            uksort(
                $this->elements,
                $order === SORT_DESC
                    ? fn(mixed $a, mixed $b): int => $type($b, $a)
                    : $type
            );
        } elseif ($order === SORT_DESC) {
            krsort($this->elements, $type);
        } else {
            ksort($this->elements, $type);
        }
    }


    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->elements;
    }
}
