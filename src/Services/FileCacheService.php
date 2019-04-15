<?php

namespace WLF_IO\MCUnmined\Services;

use WLF_IO\MCUnmined\Interfaces\CacheInterface;

class FileCacheService implements CacheInterface
{
    const VALUE = "value";
    const TTL = "ttl";

    private $file = APP_ROOT . "/cache/filecache.json";
    private $_cache = [];
    private $ttl = 0;

    public function __construct($file = null, $ttl = 0)
    {
        if (!empty($file)) {
            $this->file = $file;
        }
        $this->ttl = $ttl;
    }

    public function get(string $key)
    {
        return $this->getCacheValue($key)[self::VALUE] ?? null;
    }

    private function getCacheValue(string $key)
    {
        return $this->getCache()[$key] ?? null;
    }

    public function set(string $key, string $val): bool
    {
        $this->getCache();
        $this->_cache[$key][self::VALUE] = $val;
        $this->_cache[$key][self::TTL] = $this->ttl === 0 ? 0 : time() + $this->ttl;
        $this->saveCache();
        return true;
    }

    private function saveCache()
    {
        $dir = dirname($this->file);
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        $data = [];
        foreach ($this->getCache() as $key => $value) {
            if (!$this->valueExpired($value)) {
                $data[$key] = $value;
            }
        }
        file_put_contents($this->file, json_encode($this->_cache, JSON_PRETTY_PRINT));
    }

    private function valueExpired($value)
    {
        $time = time();
        return $value[self::TTL] < $time && $value[self::TTL] !== 0;
    }

    private function getCache()
    {
        if (empty($this->_cache)) {
            if (file_exists($this->file)) {
                $cache = json_decode(file_get_contents($this->file), true);
                if (is_array($cache)) {
                    foreach ($cache as $key => $value) {
                        if (!$this->valueExpired($value)) {
                            $this->_cache[$key] = $value;
                        }
                    }
                }
            }
        }
        return $this->_cache;
    }

    public function generateKey(array $data): string
    {
        return sha1(json_encode($data));
    }
}