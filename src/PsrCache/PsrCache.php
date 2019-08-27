<?php declare(strict_types=1);

namespace Bruha\NetteGraphQLite\PsrCache;

use Nette\Caching\Cache;
use Nette\Caching\IStorage;
use Psr\SimpleCache\CacheInterface;
use Throwable;
use Traversable;

/**
 * Class PsrCache
 *
 * @package Bruha\NetteGraphQLite\PsrCache
 */
final class PsrCache implements CacheInterface
{

    /**
     * @var Cache
     */
    private $cache;

    /**
     * PsrCache constructor
     *
     * @param IStorage $storage
     */
    public function __construct(IStorage $storage)
    {
        $this->cache = new Cache($storage);
    }

    /**
     * @param mixed $item
     * @param mixed $default
     *
     * @return mixed
     *
     * @throws PsrCacheInvalidArgumentException
     */
    public function get($item, $default = NULL)
    {
        return $this->cache->load(
            $this->check($item),
            static function () use ($default) {
                return $default;
            }
        );
    }

    /**
     * @param mixed $item
     * @param mixed $value
     * @param mixed $ttl
     *
     * @return bool
     *
     * @throws PsrCacheInvalidArgumentException
     */
    public function set($item, $value, $ttl = NULL): bool
    {
        try {
            $this->cache->save($this->check($item), $value, [Cache::EXPIRE => $ttl]);

            return TRUE;
        } catch (PsrCacheInvalidArgumentException $exception) {
            throw $exception;
        } catch (Throwable $throwable) {
            return FALSE;
        }
    }

    /**
     * @param mixed $item
     *
     * @return bool
     *
     * @throws PsrCacheInvalidArgumentException
     */
    public function delete($item): bool
    {
        try {
            $this->cache->remove($this->check($item));

            return TRUE;
        } catch (PsrCacheInvalidArgumentException $exception) {
            throw $exception;
        } catch (Throwable $throwable) {
            return FALSE;
        }
    }

    /**
     * @return bool
     */
    public function clear(): bool
    {
        try {
            $this->cache->clean();

            return TRUE;
        } catch (Throwable $throwable) {
            return FALSE;
        }
    }

    /**
     * @param mixed $items
     * @param mixed $default
     *
     * @return iterable
     *
     * @throws PsrCacheInvalidArgumentException
     */
    public function getMultiple($items, $default = NULL): iterable
    {
        return $this->cache->bulkLoad(
            $this->checkMultiple($items),
            static function () use ($default) {
                return $default;
            }
        );
    }

    /**
     * @param mixed $items
     * @param mixed $ttl
     *
     * @return bool
     *
     * @throws PsrCacheInvalidArgumentException
     */
    public function setMultiple($items, $ttl = NULL): bool
    {
        if ($items instanceof Traversable) {
            $items = iterator_to_array($items);
        }

        $this->checkMultiple(array_keys($items));

        try {
            foreach ($items as $key => $item) {
                $this->cache->save($key, $item, [Cache::EXPIRE => $ttl]);
            }

            return TRUE;
        } catch (Throwable $throwable) {
            return FALSE;
        }
    }

    /**
     * @param mixed $items
     *
     * @return bool
     *
     * @throws PsrCacheInvalidArgumentException
     */
    public function deleteMultiple($items): bool
    {
        $this->checkMultiple($items);

        try {
            foreach ($items as $item) {
                $this->cache->remove($item);
            }

            return TRUE;
        } catch (Throwable $throwable) {
            return FALSE;
        }
    }

    /**
     * @param mixed $item
     *
     * @return bool
     *
     * @throws PsrCacheInvalidArgumentException
     */
    public function has($item): bool
    {
        return $this->cache->load($this->check($item)) !== NULL;
    }

    /**
     * @param mixed $key
     *
     * @return string
     *
     * @throws PsrCacheInvalidArgumentException
     */
    private function check($key): string
    {
        if (!is_string($key) || strlen($key) === 0) {
            throw new PsrCacheInvalidArgumentException('PsrCache key must be string!');
        }

        return $key;
    }

    /**
     * @param mixed $keys
     *
     * @return array
     *
     * @throws PsrCacheInvalidArgumentException
     */
    private function checkMultiple($keys): array
    {
        if ($keys instanceof Traversable) {
            $keys = iterator_to_array($keys);
        }

        if (!is_array($keys)) {
            throw new PsrCacheInvalidArgumentException('PsrCache keys must be array or instance of Traversable!');
        }

        foreach ($keys as $key) {
            $this->check($key);
        }

        return $keys;
    }

}