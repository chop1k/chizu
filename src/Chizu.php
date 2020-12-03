<?php

namespace Chizu;

use Chizu\App\Application;
use Chizu\Config\Config;
use Chizu\Routing\Routes;

class Chizu extends Application
{
    protected function onStart(): void
    {
        Config::load(dirname(__DIR__).'/config/modules.php', $this->modules);

        $routes = new Routes();

        Config::load(dirname(__DIR__).'/config/routes.php', $routes);

        
    }

    protected function onEnd(): void
    {

    }
}