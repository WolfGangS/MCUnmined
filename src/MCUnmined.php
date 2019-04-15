<?php

namespace WLF_IO\MCUnmined;

use Slim\App;
use WLF_IO\MCUnmined\Controllers\DefaultController;

class MCUnmined
{
    private static $APP = null;
    private $slim;

    public function __construct()
    {
        $this->slim = new App();
        $this->setupRoutes();
    }

    public static function Instance()
    {
        if (self::$APP === null) {
            self::$APP = new self();
        }
        return self::$APP;
    }

    public function run()
    {
        $this->slim->run();
    }

    public function getSlim(): App
    {
        return $this->slim;
    }

    public function setupRoutes()
    {
        $this->slim->get("/v1/ping", Controllers\DefaultController::class . ":pingRequest");
        $this->slim->get("/v1/players", Controllers\PlayerController::class . ":listRequest");
        $this->slim->get("/v1/players/{id}", Controllers\PlayerController::class . ":getRequest");
        $this->slim->get("/v1/players/{id}/{prop}", Controllers\PlayerController::class . ":getPropRequest");
        $this->slim->get("/", Controllers\DefaultController::class . ":mapRequest");
    }
}