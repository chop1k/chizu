<?php

namespace Chizu;

use Chizu\App\Application;
use Chizu\Config\Config;
use Chizu\Controller\ControllerModule;
use Chizu\Event\Event;
use Chizu\Http\HttpModule;
use Chizu\Http\Request\Request;
use Chizu\Routing\Route;
use Chizu\Routing\Routes;
use Chizu\Routing\RoutingModule;
use Ds\Map;

class Chizu extends Application
{
    private function setEvents(): void
    {
        $httpDispatcher = $this->modules->getAndInitiate('http')->getDispatcher();

        $httpDispatcher->set(HttpModule::RequestEvent, new Event([function (Request $request) {
            $this->onRequest($request);
        }]));

        $routingDispatcher = $this->modules->getAndInitiate('routing')->getDispatcher();

        $routingDispatcher->set(RoutingModule::RouteNotFoundEvent, new Event([function () {
            $this->onRouteNotFound();
        }]));

        $routingDispatcher->set(RoutingModule::RouteFoundEvent, new Event([function (Route $route) {
            $this->onRouteFound($route);
        }]));
    }

    private function getController(string $name): array
    {
        $controllers = new Map();

        Config::load(dirname(__DIR__).'/config/controllers.php', $controllers);

        return $controllers->get($name);
    }

    private function onRouteNotFound(): void
    {

    }

    private function onRouteFound(Route $route): void
    {
        $controllerDispatcher = $this->modules->getAndInitiate('controller')->getDispatcher();

        [$controller, $action] = $this->getController($route->getController());

        $context = new Map();

        $context->put('controller', $controller);
        $context->put('action', $action);

        $controllerDispatcher->dispatch(ControllerModule::ControllerEvent, $context);

        echo $context->get('response');
    }

    protected function onStart(): void
    {
        Config::load(dirname(__DIR__).'/config/modules.php', $this->modules);

        $this->setEvents();

        $request = new Request();

        $request->setUrl('ok');

        $this->modules->getAndInitiate('http')->getDispatcher()->dispatch(HttpModule::RequestEvent, $request);
    }

    private function onRequest(Request $request): void
    {
        $dispatcher = $this->modules->getAndInitiate('routing')->getDispatcher();

        $routes = new Routes();

        Config::load(dirname(__DIR__).'/config/routes.php', $routes);

        $dispatcher->dispatch(RoutingModule::SearchEvent, $routes, $request->getUrl());
    }

    protected function onEnd(): void
    {

    }
}