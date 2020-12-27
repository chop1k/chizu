<?php

namespace Chizu\Controller;

use Chizu\Http\Response\Response;

/**
 * Class GlobalController represents controller with functionality for special cases.
 *
 * @package Chizu\Controller
 */
class GlobalController extends HttpController
{
    public function test(): Response
    {
        return $this->response('test', 200);
    }

    public function notFound(): Response
    {
        return $this->response('nf', 404);
    }
}