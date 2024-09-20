<?php

declare(strict_types=1);

namespace Gadget\Store;

use Gadget\Factory\FactoryInterface;
use Gadget\Io\Cast;
use Gadget\Io\File;
use Gadget\Io\JSON;

/**
 * @template TStoreElement of object
 * @extends AbstractObjectStore<TStoreElement>
 */
class JSONFileObjectStore extends AbstractObjectStore
{
    /**
     * @param FactoryInterface<TStoreElement> $factory
     * @param (callable(TStoreElement $element):string) $keyOfElement
     * @param string $path
     */
    public function __construct(
        FactoryInterface $factory,
        mixed $keyOfElement,
        protected string $path
    ) {
        parent::__construct($factory, $keyOfElement);
    }


    /** @inheritdoc */
    protected function loadObjectValues(): array
    {
        return Cast::toArray(JSON::decode(File::getContents($this->path)));
    }


    /** @inheritdoc */
    protected function commitObjectValues(array $values): bool
    {
        return File::putContents(
            $this->path,
            JSON::encode(
                $values,
                JSON_PRETTY_PRINT
            )
        ) > 0;
    }
}
