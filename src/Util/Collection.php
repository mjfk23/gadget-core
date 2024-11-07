<?php

declare(strict_types=1);

namespace Gadget\Util;

/**
 * @template K of string|int
 * @template V
 * @template-implements \IteratorAggregate<K,V>
 */
abstract class Collection implements \Countable, \IteratorAggregate, \JsonSerializable
{
    /** @var array<K,V> $elements */
    private array $elements;

    /** @var CollectionComparator<V> $comparator */
    private CollectionComparator $comparator;


    /**
     * @param array<K,V>|null $elements
     * @param CollectionComparator<V>|null $comparator
     */
    public function __construct(
        array|null $elements = null,
        CollectionComparator|null $comparator = null
    ) {
        $this
            ->setElements($elements ?? [])
            ->setComparator($comparator ?? new CollectionComparator());
    }


    /**
     * @return array<K,V>
     */
    public function getElements(): array
    {
        return $this->elements;
    }


    /**
     * @param array<K,V> $elements
     * @return static
     */
    public function setElements(array $elements): static
    {
        return $this->clear()->addElements($elements);
    }


    /**
     * @param array<K,V> $elements
     * @return static
     */
    public function addElements(array $elements): static
    {
        foreach ($elements as $k => $v) {
            $this->setElement($k, $v);
        }
        return $this;
    }


    /**
     * @param K $key
     * @return bool
     */
    public function hasElement(string|int $key): bool
    {
        return isset($this->elements[$key]);
    }


    /**
     * @param K $key
     * @param V|null $default
     * @return V|null
     */
    public function getElement(
        string|int $key,
        mixed $default = null
    ): mixed {
        return $this->hasElement($key) ? $this->elements[$key] : $default;
    }


    /**
     * @param K $key
     * @param V $value
     * @return V
     */
    public function setElement(
        string|int $key,
        mixed $value
    ): mixed {
        $this->elements[$key] = $value;
        return $this->getElement($key) ?? throw new \RuntimeException();
    }


    /**
     * @param K $key
     * @return V|null
     */
    public function removeElement(string|int $key): mixed
    {
        $element = $this->getElement($key);
        unset($this->elements[$key]);
        return $element;
    }


    /**
     * @return CollectionComparator<V>
     */
    public function getComparator(): CollectionComparator
    {
        return $this->comparator;
    }


    /**
     * @param CollectionComparator<V> $comparator
     * @return static
     */
    public function setComparator(CollectionComparator $comparator): static
    {
        $this->comparator = $comparator;
        return $this;
    }


    /**
     * @return \Traversable<K,V>
     */
    public function getIterator(): \Traversable
    {
        yield from $this->getElements();
    }


    /**
     * @return K[]
     */
    public function keys(): array
    {
        return array_keys($this->getElements());
    }


    /**
     * @return V[]
     */
    public function values(): mixed
    {
        return array_values($this->getElements());
    }


    /**
     * @return int
     */
    public function count(): int
    {
        return count($this->getElements());
    }


    /**
     * @return bool
     */
    public function empty(): bool
    {
        return $this->count() === 0;
    }


    /**
     * @return static
     */
    public function clear(): static
    {
        $this->setElements([]);
        return $this;
    }


    /**
     * @param V $element
     * @param CollectionComparator<V>|null $comparator
     * @return iterable<K,V>
     */
    public function containsElement(
        mixed $element,
        CollectionComparator|null $comparator = null
    ): iterable {
        $comparator ??= $this->getComparator();
        foreach ($this->getIterator() as $key => $value) {
            if ($comparator->equals($element, $value)) {
                yield $key => $value;
            }
        }
    }


    /**
     * @param int $flags
     * @param CollectionComparator<V>|null $comparator
     * @return static
     */
    public function sort(
        int $flags = SORT_REGULAR,
        CollectionComparator|null $comparator = null
    ): static {
        $type = array_reduce(
            array_map(
                fn(array $f): int => ($flags & $f[0]) === $f[0] ? $f[1] : 0,
                [[64, 4], [32, 2], [SORT_DESC, 1]]
            ),
            fn (int $c, int $f): int => $c | $f,
            0
        );
        $flags = array_reduce(
            array_map(
                fn(int $f): int => $flags & $f,
                [SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING, SORT_NATURAL, SORT_FLAG_CASE]
            ),
            fn(int $carry, int $f): int => $carry | $f,
            0
        );
        $comparator ??= $this->getComparator();

        match ($type) {
            7 => uksort($this->elements, fn($a, $b) => $b <=> $a),
            6 => uksort($this->elements, fn($a, $b) => $a <=> $b),
            5 => usort($this->elements, $comparator->compareDesc(...)),
            4 => usort($this->elements, $comparator->compare(...)),
            3 => krsort($this->elements, $flags),
            2 => ksort($this->elements, $flags),
            1 => rsort($this->elements, $flags),
            default => sort($this->elements, $flags)
        };
        return $this;
    }


    /**
     * @return mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->getElements();
    }
}
