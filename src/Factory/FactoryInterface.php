<?php

declare(strict_types=1);

namespace Gadget\Factory;

/**
 * @template TElement of object
 */
interface FactoryInterface
{
    /**
     * @return \ReflectionClass<TElement>
     */
    public function getClass(): \ReflectionClass;


    /**
     * @param mixed $values
     * @return TElement
     */
    public function create(mixed $values): object;
}
