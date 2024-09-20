<?php

declare(strict_types=1);

namespace Gadget\Factory;

/**
 * @template TElement of object
 * @implements FactoryInterface<TElement>
 */
abstract class AbstractFactory implements FactoryInterface
{
    /**
     * @param class-string<TElement> $className
     */
    public function __construct(protected string $className)
    {
    }


    /** @inheritdoc */
    public function getClass(): string
    {
        return $this->className;
    }


    /** @inheritdoc */
    abstract public function create(mixed $values): object;
}
