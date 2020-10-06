<?php

namespace App\Database;

/**
 * Interface Connection represents database connection
 *
 * @package App\Database
 */
interface Connection {
    public const Read = 1;
    public const Insert = 2;
    public const Update = 3;
    public const Root = 4;

    /**
     * Returns raw connector
     *
     * @return mixed
     */
    public function getRaw();

    /**
     * Connecting to database
     */
    public function connect(): void;

    /**
     * Close connection
     */
    public function close(): void;

    /**
     * Executes query
     *
     * @param string $query
     * @return Result
     */
    public function query(string $query): Result;

    /**
     * Returns true if connection established
     *
     * @return mixed
     */
    public function isConnected();

    /**
     * Returns connection host
     *
     * @return string
     */
    public function getHost(): string;

    /**
     * Sets connection host
     *
     * @param string $host
     */
    public function setHost(string $host): void;

    /**
     * Returns connection port
     *
     * @return int
     */
    public function getPort(): int;

    /**
     * Sets connection port
     *
     * @param int $port
     */
    public function setPort(int $port): void;

    /**
     * Returns connection database name
     *
     * @return string
     */
    public function getDatabase(): string;

    /**
     * Sets database name of connection
     *
     * @param string $name
     */
    public function setDatabase(string $name): void;

    /**
     * Returns connection password
     *
     * @return string
     */
    public function getPassword(): string;

    /**
     * Sets connection password
     *
     * @param string $password
     */
    public function setPassword(string $password): void;

    /**
     * Returns connection username
     *
     * @return string
     */
    public function getUser(): string;

    /**
     * Sets connection username
     *
     * @param string $name
     */
    public function setUser(string $name): void;
}