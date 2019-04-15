<?php

namespace WLF_IO\MCUnmined\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use WLF_IO\MCUnmined\Services\PlayerService;

class PlayerController
{
    private $service;

    public function __construct()
    {
        $this->service = new PlayerService();
    }

    public function listRequest(Request $request, Response $response) {
        $response->getBody()->write(json_encode($this->service->getPlayerData()));
        return $response;
    }
}