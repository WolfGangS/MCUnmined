<?php

namespace WLF_IO\MCUnmined\Services;

use WLF_IO\MCUnmined\Interfaces\CacheInterface;

class FileCacheService implements CacheInterface
{
    const VALUE = "value";
    const TTL = "ttl";

    private $file = APP_ROOT . "/cache/filecache.json";
    private $_cache = [];

    public function __construct($file = null)
    {
        if (!empty($file)) {
            $this->file = $file;
        }
    }

    public function get(string $key)
    {
        return $this->getCacheValue($key)["value"];
    }

    private function getCacheValue(string $key)
    {
        return $this->getCache()[$key] ?? null;
    }

    public function set(string $key, string $val, int $ttl = 0): bool
    {
        $this->getCache();
        $this->_cache[$key]["value"] = $val;
        $this->_cache[$key][self::TTL] = $ttl === 0 ? 0 : time() + $ttl;
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
        $time = time();
        foreach ($this->getCache() as $key => $value) {
            if ($value[self::TTL] > $time) {
                $data[$key] = $value;
            }
        }
        file_put_contents($this->file, json_encode($this->_cache, JSON_PRETTY_PRINT));
    }

    private function getCache()
    {
        if (empty($this->_cache)) {
            if (file_exists($this->file)) {
                $cache = json_decode(file_get_contents($this->file), true);
                if (is_array($cache)) {
                    $time = time();
                    foreach ($cache as $key => $value) {
                        if ($value[self::TTL] > $time) {
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