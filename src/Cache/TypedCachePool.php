<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface;

/** @template T */
abstract class TypedCachePool
{
    /**
     * @param CacheItemPool $cache
     */
    public function __construct(private CacheItemPool $cache)
    {
        $this->cache = $cache->withNamespace(static::class);
    }


    /**
     * @return CacheItemPool
     */
    protected function getCache(): CacheItemPool
    {
        return $this->cache;
    }


    /**
     * @param CacheItemPool $cache
     * @return static
     */
    protected function setCache(CacheItemPool $cache): static
    {
        $this->cache = $cache;
        return $this;
    }


    /**
     * @param mixed $v
     * @return T|null
     */
    abstract protected function toValue(mixed $v): mixed;


    /**
     * @param CacheItemInterface $item
     * @return T|null
     */
    abstract protected function create(CacheItemInterface $item): mixed;


    /**
     * @param string $key
     * @return T|null
     */
    public function get(string $key): mixed
    {
        $item = $this->cache->get($key);
        $value = ($item->isHit()) ? $this->toValue($item->get()) : null;
        return $value ?? $this->set($key, $this->create($item));
    }


    /**
     * @param string $key
     * @param T|null $value
     * @return T|null
     */
    public function set(
        string $key,
        mixed $value
    ): mixed {
        if ($value !== null) {
            $this->cache->save($this->cache->get($key)->set($value));
        } else {
            $this->delete($key);
        }
        return $value;
    }


    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        $cacheItem = $this->cache->get($key);
        return $cacheItem->isHit() ? $this->cache->delete($cacheItem) : false;
    }
}
