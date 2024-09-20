<?php

declare(strict_types=1);

namespace Gadget\Util;

/**
 * @template TElement
 * @extends Collection<TElement>
 */
class Stack extends Collection
{
    /**
     * @param TElement[] $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct();
        while (count($elements) > 0) {
            $this->push(array_pop($elements));
        }
    }


    /**
     * @return TElement|null
     */
    public function peek(): mixed
    {
        return $this->elements[0] ?? null;
    }


    /**
     * @param TElement $element
     * @return void
     */
    public function push(mixed $element): void
    {
        array_unshift($this->elements, $element);
    }


    /**
     * @return TElement|null
     */
    public function pop(): mixed
    {
        return array_shift($this->elements);
    }
}
