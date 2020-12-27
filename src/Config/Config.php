<?php

namespace Chizu\Config;

use Ds\Map;
use InvalidArgumentException;

/**
 * Class Config represents wrapper for map which contains configurators.
 *
 * @package Chizu\Config
 */
class Config
{
    /**
     * Contains configurators.
     *
     * @var Map $config
     */
    protected Map $config;

    /**
     * Returns true if config contains configurator.
     *
     * @param string $file
     * @return bool
     */
    public function has(string $file): bool
    {
        return $this->config->hasKey($file);
    }

    /**
     * Adds and loads config.
     *
     * @param string $file
     * @param $configurator
     * @param mixed ...$other
     */
    public function add(string $file, $configurator, ...$other)
    {
        $this->config->put($file, $configurator);

        $this->load(sprintf('%s/%s.php', $this->path, $file), $configurator, ...$other);
    }

    /**
     * Returns configurator.
     *
     * @param string $file
     *
     * @return mixed
     */
    public function get(string $file)
    {
        return $this->config->get($file);
    }

    /**
     * Contains path to config directory.
     *
     * @var string $path
     */
    protected string $path;

    /**
     * Returns path to config directory.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Sets path to config directory.
     *
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * Config constructor.
     *
     * @param string $path
     * @param array $values
     */
    public function __construct(string $path, $values = [])
    {
        $this->path = $path;
        $this->config = new Map($values);
    }

    /**
     * Load config with given data.
     * Config file must return function, configurator and other will be passed to function as arguments.
     *
     * @param string $path
     * @param $configurator
     * @param mixed ...$other
     */
    protected function load(string $path, $configurator, ...$other): void
    {
        $handler = require $path;

        if (!is_callable($handler))
        {
            throw new InvalidArgumentException(sprintf('Config file "%s" must return callable function', $path));
        }

        $handler($configurator, ...$other);
    }
}