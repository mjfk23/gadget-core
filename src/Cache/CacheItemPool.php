<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;

final class CacheItemPool
{
    /**
     * @param CacheItemPoolInterface $cache
     * @param string[] $namespace
     */
    public function __construct(
        private CacheItemPoolInterface $cache,
        private array $namespace = []
    ) {
    }


    /**
     * @param string $namespace
     * @return self
     */
    public function withNamespace(...$namespace): self
    {
        return new self(
            $this->cache,
            $namespace
        );
    }


    /**
     * @return CacheItemPoolInterface
     */
    public function getCacheItemPoolInterface(): CacheItemPoolInterface
    {
        return $this->cache;
    }


    /**
     * @param string $key
     * @return string
     */
    public function key(...$key): string
    {
        return hash('SHA256', implode("::", [...$this->namespace, ...$key]));
    }


    /**
     * @param string $key
     * @return CacheItemInterface
     */
    public function get(...$key): CacheItemInterface
    {
        return $this->cache->getItem($this->key(...$key));
    }


    /**
     * @param CacheItemInterface $item
     * @return bool
     */
    public function save(CacheItemInterface $item): bool
    {
        return $this->cache->save($item);
    }


    /**
     * @param CacheItemInterface $item
     * @return bool
     */
    public function delete(CacheItemInterface $item): bool
    {
        return $this->cache->deleteItem($item->getKey());
    }
}
