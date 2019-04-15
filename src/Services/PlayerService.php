<?php

namespace WLF_IO\MCUnmined\Services;

use Nbt;

class PlayerService
{
    private $nbtService;
    private $_playerData;

    public function __construct()
    {
        $this->nbtService = new Nbt\Service(new Nbt\DataHandler());
    }

    public function getPlayerProps(string $keys, string $props)
    {
        $keys = explode(",", $keys);
        $players = $this->getPlayerData();
        $result = [];
        foreach ($keys as $key) {
            $key = strtolower($key);
            foreach ($players as $id => $player) {
                if ($id !== $key && $key !== "all") {
                    $result[$key] = $this->getPropsFromArray($player, $props);
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
        $keys = strtolower($keys);
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
                $players[strtolower(implode($name))] = $this->nbtService->loadFile($file->getRealPath())->__toArray();
            }
        }
        return $players;
    }
}