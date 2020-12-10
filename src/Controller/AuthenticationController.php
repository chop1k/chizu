<?php

namespace Chizu\Controller;

use Chizu\Http\Response\Response;
use Ds\Map;

class AuthenticationController
{
    protected Map $context;

    public function __construct(Map $context)
    {
        $this->context = $context;
    }

    public function authenticate(): Response
    {
        var_dump($this->context->toArray());
        return new Response();
    }
}