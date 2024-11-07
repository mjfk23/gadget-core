<?php

declare(strict_types=1);

namespace Gadget\Util;

/**
 * @template V
 * @template-extends Collection<int,V>
 */
class Queue extends Collection
{
    /**
     * @return V|null
     */
    public function peek(): mixed
    {
        return !$this->empty()
            ? $this->getElement(0)
            : null;
    }


    /**
     * @param V $value
     * @return static
     */
    public function enqueue(...$value): static
    {
        foreach ($value as $v) {
            $this->setElement($this->count(), $v);
        }
        return $this;
    }


    /**
     * @return V|null
     */
    public function dequeue(): mixed
    {
        return !$this->empty()
            ? $this->removeElement(0)
            : null;
    }
}
