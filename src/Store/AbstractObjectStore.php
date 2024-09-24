<?php

declare(strict_types=1);

namespace Gadget\Store;

use Gadget\Factory\FactoryInterface;
use Gadget\Io\Cast;
use Gadget\Util\Map;

/**
 * @template TStoreElement of object
 * @implements ObjectStoreInterface<TStoreElement>
 */
abstract class AbstractObjectStore implements ObjectStoreInterface
{
    /** @var Map<TStoreElement> $objectMap */
    private Map $objectMap;


    /**
     * @param FactoryInterface<TStoreElement> $factory
     * @param (callable(TStoreElement $element):string) $keyOfElement
     */
    public function __construct(
        private FactoryInterface $factory,
        private mixed $keyOfElement
    ) {
        $this->objectMap = $this->createObjectMap([]);
    }


    /** @inheritdoc */
    public function load(): bool
    {
        $this->objectMap = $this->createObjectMap($this->loadObjectValues());
        return true;
    }


    /**
     * @param mixed[] $values
     * @return Map<TStoreElement>
     */
    protected function createObjectMap(array $values): Map
    {
        return new Map(
            Cast::toTypedArray(
                $values,
                /**
                 * @param mixed $value
                 * @return TStoreElement
                 */
                fn(mixed $value): object => $this->factory->create($value)
            ),
            $this->key(...)
        );
    }


    /**
     * @return mixed[]
     */
    abstract protected function loadObjectValues(): array;


    /** @inheritdoc */
    public function key(string|object $keyOrElement): string
    {
        return is_object($keyOrElement)
            ? ($this->keyOfElement)($keyOrElement)
            : $keyOrElement;
    }


    /** @inheritdoc */
    public function has(string $key): bool
    {
        return $this->objectMap->has($this->key($key));
    }


    /** @inheritdoc */
    public function get(string $key): object|null
    {
        return $this->objectMap->get($this->key($key));
    }


    /** @inheritdoc */
    public function save(object $element): bool
    {
        $key = $this->key($element);
        $this->objectMap->set($key, $element);
        return $this->has($key);
    }


    /** @inheritdoc */
    public function delete(string|object $keyOrElement): bool
    {
        $key = $this->key($keyOrElement);
        $this->objectMap->remove($key);
        return !$this->has($key);
    }


    /** @inheritdoc */
    public function commit(): bool
    {
        return $this->commitObjectValues($this->objectMap->toArray());
    }


    /**
     * @param mixed[] $values
     * @return bool
     */
    abstract protected function commitObjectValues(array $values): bool;


    /** @inheritdoc */
    public function getIterator(): \Traversable
    {
        return $this->objectMap->getIterator();
    }
}
