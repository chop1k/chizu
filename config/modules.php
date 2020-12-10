<?php

use Chizu\Controller\ControllerModule;
use Chizu\Http\HttpModule;
use Chizu\Module\Modules;
use Chizu\Routing\RoutingModule;

return static function(Modules $modules): void
{
    $modules->add('http', HttpModule::class);
    $modules->add('routing', RoutingModule::class);
    $modules->add('controller', ControllerModule::class);
};