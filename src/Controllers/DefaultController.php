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
}