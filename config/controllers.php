<?php

use Chizu\Controller\GlobalController;
use Ds\Map;

/**
 * This function defines controller.
 *
 * @param Map $controllers
 * Map for adding controllers.
 */
return static function(Map $controllers): void
{
    $controllers->put('global.nf', [GlobalController::class, 'notFound']);
    $controllers->put('global.test', [GlobalController::class, 'test']);
};