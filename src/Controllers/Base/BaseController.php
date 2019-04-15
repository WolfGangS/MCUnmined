<?php

namespace WLF_IO\MCUnmined\Controllers\Base;

use Slim\Http\Request;
use Slim\Http\Response;

abstract class BaseController
{
    private function responseWithJson($json, Response $response)
    {
        return $response->withJson(array_filter($json));
    }

    protected function success($data, Response $response)
    {
        return $this->responseWithJson(
            [
                "status" => "success",
                "data" => $data
            ], $response
        );
    }

    protected function failure(string $reason, Response $response, $data = [])
    {
        return $this->responseWithJson(
            [
                "status" => "failure",
                "reason" => $reason,
                "data" => $data
            ], $response
        );
    }
}