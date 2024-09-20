<?php

declare(strict_types=1);

namespace Gadget\Factory;

/**
 * @template TElement of object
 */
interface FactoryInterface
{
    /**
     * @return class-string<TElement>
     */
    public function getClass(): string;


    /**
     * @param mixed $values
     * @return TElement
     */
    public function create(mixed $values): object;
}
