<?php

use Chizu\Controller\AuthenticationController;
use Ds\Map;

return static function(Map $controllers): void
{
    $controllers->put('authentication', AuthenticationController::class);
};