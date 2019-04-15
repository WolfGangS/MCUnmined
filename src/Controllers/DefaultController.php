<?php

namespace WLF_IO\MCUnmined\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

class DefaultController
{
    public function pingRequest(Request $request, Response $response)
    {
        $response->getBody()->write("pong");
        return $response;
    }

    public function mapRequest(Request $request, Response $response)
    {
        $file = APP_ROOT . "/public/unmined.index.html";
        if (file_exists($file)) {
            $content = file_get_contents($file);
        } else {
            $content = "Missing Map";
        }
        $response->getBody()->write($content);
        return $response;
    }
}