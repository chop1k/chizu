<?php

namespace App\Database;

/**
 * Class Query represents abstract class for queries
 *
 * @package App\Database
 */
abstract class Query
{
    /**
     * Returns query unique name
     *
     * @return string|null
     */
    abstract public function getName(): ?string;

    /**
     * Returns connection type that will be used
     *
     * @return int
     */
    abstract public function getType(): int;

    /**
     * Returns raw query
     *
     * @return string
     */
    abstract public function getQuery(): string;

    /**
     * Returns array of query parameters
     *
     * @return array
     */
    abstract public function getParams(): array;

    /**
     * Handler function which need for handling query parameters
     * If parameters is invalid it must throw exception
     */
    abstract public function handleParams(): void;

    /**
     * Shortcut for creating query from raw query and parameters
     *
     * @param string $query
     * @param array $params
     * @return string
     */
    public static function construct(string $query, array $params): string
    {
        if (count($params) <= 0)
            return $query;

        $cursor = stripos($query, ':'.$params[array_key_first($params)]);

        $pointers = [];

        foreach ($params as $key => $value)
        {
            $pos = stripos($query,":$key");

            if ($pos === false)
                continue;

            $pointers[$key] = -(strlen($query) - $pos);
        }

        foreach ($pointers as $name => $pointer)
        {
            $value = $params[$name];

            $type = gettype($value);

            if ($type === 'string')
            {
                $value = str_replace(['\'', '"'], ['\\\'','\"'], $value);

                $value = "'$value'";
            }

            $query = substr_replace($query, $value, $pointer, strlen(":$name"));

            $cursor += strlen($value);
        }

        return $query;
    }
}