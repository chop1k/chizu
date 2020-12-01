<?php

namespace Chizu;

use Chizu\App\Application;
use Chizu\Config\Config;

class Chizu extends Application
{
    protected function onStart(): void
    {
        Config::load(dirname(__DIR__).'/config/modules.php', $this->modules);
    }

    protected function onEnd(): void
    {

    }
}