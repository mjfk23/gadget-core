<?php

declare(strict_types=1);

namespace Gadget\Cache;

use Psr\Cache\CacheItemInterface;

/** @template T */
abstract class TypedCachePool
{
    private int|null $expiresAfter = null;


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
     * @return int
     */
    public function getExpiresAfter(): int|null
    {
        return $this->expiresAfter;
    }


    /**
     * @param int|null $expiresAfter
     * @return static
     */
    public function setExpiresAfter(int|null $expiresAfter): static
    {
        $this->expiresAfter = $expiresAfter;
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
     * @param bool $skipCreate
     * @return T|null
     */
    public function get(
        string $key,
        bool $skipCreate = false
    ): mixed {
        $item = $this->cache->get($key);
        $value = ($item->isHit()) ? $this->toValue($item->get()) : null;
        return $value ?? (!$skipCreate ? $this->set($key, $this->create($item)) : null);
    }


    /**
     * @param string $key
     * @param T|null $value
     * @param int|null $expiresAfter
     * @return T|null
     */
    public function set(
        string $key,
        mixed $value,
        int|null $expiresAfter = null
    ): mixed {
        $expiresAfter ??= $this->getExpiresAfter();

        if ($value !== null) {
            $item = $this->cache->get($key)->set($value);
            if (is_int($expiresAfter)) {
                $item = $item->expiresAfter($expiresAfter);
            }
            $this->cache->save($item);
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
