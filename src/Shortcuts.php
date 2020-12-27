<?php

namespace Chizu;

use Chizu\Http\Request\Request;

/**
 * Class Shortcuts contains shortcuts as static functions.
 *
 * @package Chizu
 */
class Shortcuts
{
    /**
     * Shortcut for creating a request from globals.
     *
     * @return Request
     */
    public static function createRequestFromGlobals(): Request
    {
        $request = new Request();

        $request->setPort($_SERVER['REMOTE_PORT']);
        $request->setAddress($_SERVER['REMOTE_ADDR']);
        $request->setMethod($_SERVER['REQUEST_METHOD']);
        $request->setBody(file_get_contents('php://input'));
        $request->setTime($_SERVER['REQUEST_TIME']);
        $request->setUrl($_SERVER['REQUEST_URI']);

        foreach ($_SERVER as $key => $value)
        {
            if (substr($key, 0 , 5) !== 'HTTP_')
                continue;

            $key = substr($key, 5, strlen($key));

            $request->getHeaders()->put($key, $value);
        }

        return $request;
    }
}