<?php

namespace Chizu;

use Chizu\App\Application;
use Chizu\Config\Config;
use Chizu\Controller\ControllerModule;
use Chizu\DI\Dependency;
use Chizu\DI\Exception\DIException;
use Chizu\Event\Event;
use Chizu\Http\HttpModule;
use Chizu\Http\Request\Request;
use Chizu\Http\Response\Response;
use Chizu\Module\Module;
use Chizu\Routing\Route;
use Chizu\Routing\Routes;
use Chizu\Routing\RoutingModule;
use Ds\Map;
use Exception;
use ReflectionException;

/**
 * Class Chizu represents main class of chizu application.
 *
 * @package Chizu
 */
class Chizu extends Application
{
    /**
     * @inheritdoc
     */
    public const StartEvent = 'chizu.start';

    /**
     * @inheritdoc
     */
    public const EndEvent = 'chizu.end';

    /**
     * Executes for config loading.
     */
    public const ConfigEvent = 'chizu.config';

    /**
     * Executes after searching.
     *
     * @param Route|null $route
     * Null if not found, Route instance otherwise.
     *
     * @throws DIException
     *
     * @throws ReflectionException
     */
    private function onRoute(?Route $route): void
    {
        /**
         * @var Map $controllers
         */
        $controllers = $this->container->createByKey(Config::class)->get('controllers');

        $context = new Map();

        if (!is_null($route))
        {
            [ $controller, $method ] = $controllers->get($route->getController());
        }
        else
        {
            [ $controller, $method ] = $controllers->get('global.nf');
        }

        $context->putAll([
            'controller' => $controller,
            'method' => $method
        ]);

        $this->events->get(ControllerModule::ControllerEvent)->execute($context);

        if ($context->hasKey('response'))
        {
            $response = $context->get('response');
        }
        else
        {
            $response = new Response();

            $response->setStatus(500);

            var_dump($context);
        }

        $this->events->get(HttpModule::ResponseEvent)->execute($response);
    }

    /**
     * Executes to load configuration.
     *
     * @throws DIException
     *
     * @throws ReflectionException
     *
     * @throws Exception
     */
    private function onConfig(): void
    {
        /**
         * @var Config $config
         */
        $config = $this->container->createByKey(Config::class);

        $config->add('modules', $this->modules, $this->events, $this->container);

        /**
         * @var Module $module
         */
        foreach ($this->modules->getIterator() as $module)
        {
            if ($this->events->has($module::InitiationEvent))
            {
                $this->events->get($module::InitiationEvent)->execute();
            }
        }

        $config->add('routes', new Routes());
        $config->add('controllers', new Map());
    }

    /**
     * @inheritDoc
     */
    protected function onStart(): void
    {
        $this->events->get(self::ConfigEvent)->execute();

        $this->events->get(HttpModule::RequestEvent)->execute(Shortcuts::createRequestFromGlobals());
    }

    /**
     * Chizu constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->events->set(HttpModule::RequestEvent, Event::createByMethod($this, 'onRequest'));
        $this->events->set(HttpModule::ResponseEvent, Event::createByMethod($this, 'onResponse'));
        $this->events->set(RoutingModule::ResultEvent, Event::createByMethod($this, 'onRoute'));

        $this->events->set(self::ConfigEvent, Event::createByMethod($this, 'onConfig'));

        $this->container->add(Config::class, new Dependency(new Config(dirname(__DIR__).'/config')));
    }

    /**
     * Executes after loading config.
     *
     * @param Request $request
     * Http request instance.
     *
     * @throws DIException
     *
     * @throws ReflectionException
     */
    private function onRequest(Request $request): void
    {
        $this->events->get(RoutingModule::SearchEvent)->execute(
            $this->container->createByKey(Config::class)->get('routes'),
            $request->getUrl()
        );
    }

    /**
     * Executes when response received.
     *
     * @param Response $response
     *
     * @throws Exception
     */
    private function onResponse(Response $response): void
    {
        $response->send();
    }

    /**
     * @inheritDoc
     */
    protected function onEnd(): void
    {

    }
}