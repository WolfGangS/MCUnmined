<?php

namespace WLF_IO\MCUnmined\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;
use WLF_IO\MCUnmined\Controllers\Base\BaseController;
use WLF_IO\MCUnmined\Services\PlayerService;

class PlayerController extends BaseController
{
    private $service;

    public function __construct()
    {
        $this->service = new PlayerService();
    }

    public function listRequest(Request $request, Response $response)
    {
        return $this->success($this->service->getPlayerData(), $response);
    }

    public function getRequest(Request $request, Response $response, $args)
    {
        return $this->success($this->service->getPlayerData(), $args["id"]);
    }
}