<?php

namespace WLF_IO\MCUnmined\Services;

use Nbt;

class PlayerService
{
    private $nbtService;
    private $tree;

    public function __construct()
    {
        $this->nbtService = new Nbt\Service(new Nbt\DataHandler());
        $this->tree = $this->nbtService->loadFile(APP_ROOT . "/public/wolfgang.dat");
    }

    public function printTree(){
        echo json_encode($this->tree->findChildByName("Pos"),JSON_PRETTY_PRINT);
        die();
    }
}