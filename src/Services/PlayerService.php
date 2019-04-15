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

    public function getPlayerData()
    {
        if (empty($this->_playerData)) {
            $this->_playerData = $this->fetchPlayerData();
        }
        return $this->_playerData;
    }

    private function fetchPlayerData()
    {
        $players = [];
        $path = "/home/minecraft/1.14-pre-2/world/playerdata";
        $iterator = new \DirectoryIterator($path);
        foreach ($iterator as $file) {
            if(!$file->isFile() || $file->isDot()){
                continue;
            }
            if($file->getExtension() == "dat"){
                $players[$file->getFilename()] = $this->nbtService->loadFile($file->getRealPath());
            }
        }
        return $players;
    }
}