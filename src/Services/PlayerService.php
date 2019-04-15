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

    public function getPlayerProp(string $key, string $prop)
    {
        $data = $this->getPlayerData($key);
        if($key == "all"){
            $props = [];
            foreach ($data as $k=>$v){
                $props[$k] = $v[$prop] ?? null;
            }
            return $props;
        }
        return $data[$prop] ?? null;
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
                $players[implode($name)] = $this->nbtService->loadFile($file->getRealPath())->__toArray();
            }
        }
        return $players;
    }
}