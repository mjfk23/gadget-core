<?php

declare(strict_types=1);

namespace Gadget\Util;

/**
 * @template V
 * @template-extends Collection<int,V>
 */
class Stack extends Collection
{
    /**
     * @return V|null
     */
    public function peek(): mixed
    {
        return !$this->empty()
            ? $this->getElement($this->count() - 1)
            : null;
    }


    /**
     * @param V $value
     * @return static
     */
    public function push(...$value): static
    {
        foreach ($value as $v) {
            $this->setElement($this->count(), $v);
        }
        return $this;
    }


    /**
     * @return V|null
     */
    public function pop(): mixed
    {
        return !$this->empty()
            ? $this->removeElement($this->count() - 1)
            : null;
    }
}
