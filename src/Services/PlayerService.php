<?php

namespace WLF_IO\MCUnmined\Services;

use Nbt;

class PlayerService
{
    private $nbtService;
    private $_playerData;
    private $http;

    public function __construct()
    {
        $this->nbtService = new Nbt\Service(new Nbt\DataHandler());
        $this->http = new HTTPCacheService("https://sessionserver.mojang.com/session/minecraft/profile/",
            new FileCacheService());
    }

    public function getPlayerProps(string $keys, string $props)
    {
        $keys = explode(",", $keys);
        $players = $this->getPlayerData();
        $result = [];
        foreach ($keys as $key) {
            $key = strtolower($key);
            foreach ($players as $id => $player) {
                if ($id == $key || $key == "all") {
                    $result[$id] = $this->getPropsFromArray($player, $props);
                }
            }
        }
        if (count($result) == 1) {
            $result = $result[$keys[0]];
        }
        return $result;
    }

    private function getPropsFromArray(array $data, string $keys)
    {
        if ($keys === "all") {
            return $data;
        }
        $keys = explode(",", $keys);
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = $data[$key] ?? null;
        }
        if (count($result) == 1) {
            $result = $result[$keys[0]];
        }
        return $result;
    }

    public function getPlayerData($key = null)
    {
        if (empty($this->_playerData)) {
            $this->_playerData = $this->fetchPlayerData();
        }
        return $this->_playerData[$key] ?? $this->_playerData;
    }

    private function fetchPlayerData()
    {
        $players = [];
        $path = "/home/minecraft/1.14-pre-2/world/playerdata";
        $iterator = new \DirectoryIterator($path);
        foreach ($iterator as $file) {
            if (!$file->isFile() || $file->isDot()) {
                continue;
            }
            if ($file->getExtension() == "dat") {
                $name = explode(".", $file->getFilename());
                array_pop($name);
                $name = strtolower(implode($name));
                $players[$name] = $this->nbtService->loadFile($file->getRealPath())->__toArray();
                $players[$name]["Web"] = $this->getPlayerWebData($name);
            }
        }
        return $players;
    }

    private function getPlayerWebData($key)
    {
        $key = implode("", explode("-", $key));
        $_data = $this->http->get($key);
        $data["name"] = $_data["name"];
        $props = $_data["properties"] ?? [];

        foreach ($props as $prop) {
            $name = $prop["name"] ?? null;
            echo "$key -- $name";
            $value = $prop["value"] ?? null;
            if (!empty($name) && !empty($value)) {
                $value = $this->getWebPropValue($name, $value);
                if (!empty($value)) {
                    $data[$name] = $value;
                }
            }
        }
        die();

        return $data;
    }

    private function getWebPropValue($name, $value)
    {
        switch ($name) {
            case "textures":
                return json_decode(base64_decode($value), true);
        }
        return null;
    }
}