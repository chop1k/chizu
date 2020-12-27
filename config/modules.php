<?php

use Chizu\Controller\ControllerModule;
use Chizu\DI\Container;
use Chizu\Event\Events;
use Chizu\Http\HttpModule;
use Chizu\Routing\RoutingModule;
use Ds\Map;

/**
 * This function defines modules.
 *
 * @param Map $modules
 * Map for adding modules and passing to the module's constructor.
 *
 * @param Events $events
 * Events instance required to be passed to the module's constructor.
 *
 * @param Container $container
 * Container instance required to be passed to the module's constructor.
 */
return static function(Map $modules, Events $events, Container $container): void
{
    $modules->put(HttpModule::class, new HttpModule($events, $container, $modules));
    $modules->put(RoutingModule::class, new RoutingModule($events, $container, $modules));
    $modules->put(ControllerModule::class, new ControllerModule($events, $container, $modules));
};