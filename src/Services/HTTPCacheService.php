<?php

namespace WLF_IO\MCUnmined\Services;

use GuzzleHttp\Client as GuzzleClient;
use WLF_IO\MCUnmined\Interfaces\CacheInterface;

class HTTPCacheService
{
    private $guzzle;
    private $baseUri;
    private $cache;

    public function __construct(string $baseUri, CacheInterface $cache)
    {
        $this->cache = $cache;
        $this->baseUri = $baseUri;
        $this->guzzle = new GuzzleClient([
            'base_uri' => $baseUri,
            'timeout'  => 30.0,
            'headers' => array_merge(
                [
                    'Accept' => 'application/json'
                ]
            )
        ]);
    }

    public function get($url){
        $key = $this->cache->generateKey([$this->baseUri,"get",$url]);
        $result = $this->cache->get($key);
        if(empty($result)){
            $response = $this->guzzle->get($url);
            $response = $response->getBody()->getContents();
            $response = json_decode($response,true);
            if(is_array($response)){
                $result = $response;
                $this->cache->set($key,json_encode($result));
            }
        } else {
            $result = json_decode($result,true);
        }
        return $result;
    }
}