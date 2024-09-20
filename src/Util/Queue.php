<?php

declare(strict_types=1);

namespace Gadget\Util;

/**
 * @template TElement
 * @extends Collection<TElement>
 */
class Queue extends Collection
{
    /**
     * @param TElement[] $elements
     */
    public function __construct(array $elements = [])
    {
        parent::__construct();
        foreach ($elements as $element) {
            $this->add($element);
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
    public function add(mixed $element): void
    {
        array_push($this->elements, $element);
    }


    /**
     * @return TElement|null
     */
    public function remove(): mixed
    {
        return array_shift($this->elements);
    }
}
