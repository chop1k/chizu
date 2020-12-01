<?php

namespace Chizu\Config;

use InvalidArgumentException;

class Config
{
    public static function load(string $path, ...$data): void
    {
        $handler = require $path;

        if (!is_callable($handler))
        {
            throw new InvalidArgumentException(sprintf('Config file "%s" must return callable function'));
        }

        $handler(...$data);
    }
}