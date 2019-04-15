<?php


namespace WLF_IO\MCUnmined\Interfaces;


interface CacheInterface
{
    public function get(string $key);
    public function set(string $key, string $val, int $ttl = 0) : bool;
    public function generateKey(array $data) : string;
}