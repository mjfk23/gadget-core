<?php

declare(strict_types=1);

namespace Gadget\Factory;

/**
 * @template TElement of object
 * @implements FactoryInterface<TElement>
 */
abstract class AbstractFactory implements FactoryInterface
{
    /** @var \ReflectionClass<TElement> $class*/
    private \ReflectionClass $class;


    /**
     * @param \ReflectionClass<TElement>|class-string<TElement> $class
     */
    public function __construct(\ReflectionClass|string $class)
    {
        $this->class = $class instanceof \ReflectionClass
            ? $class
            : new \ReflectionClass($class);
    }


    /**
     * @return \ReflectionClass<TElement>
     */
    public function getClass(): \ReflectionClass
    {
        return $this->class;
    }


    /**
     * @param mixed $values
     * @return TElement
     */
    abstract public function create(mixed $values): object;
}
