<?php

namespace WLF_IO\MCUnmined\Services;

use WLF_IO\MCUnmined\Interfaces\CacheInterface;

class FileCacheService implements CacheInterface
{
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
        return $this->getCache()[$key] ?? null;
    }

    public function set(string $key, string $val, int $ttl = 0): bool
    {
        $this->_cache[$key] = $val;
        $this->saveCache();
        return true;
    }

    private function saveCache(){
        if(!file_exists(APP_ROOT . "/cache")){
            mkdir(APP_ROOT . "/cache");
        }
        file_put_contents($this->file, json_encode($this->_cache, JSON_PRETTY_PRINT));
    }

    private function getCache()
    {
        if(empty($this->_cache)){
            if(file_exists($this->file)) {
                $cache = json_decode(file_get_contents($this->file), true);
                if (is_array($cache)) {
                    $this->_cache = $cache;
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