<?php

use Chizu\Routing\Route;
use Chizu\Routing\Routes;

/**
 * This function defines routes.
 *
 * @param Routes $routes
 */
return static function(Routes $routes): void
{
    $routes->push(new Route('test', '/a', 'global.test'));
};