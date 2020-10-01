<?php

namespace App\Service;

use App\Database\Connection;
use App\Database\Query;
use App\Database\Result;
use App\Exception\QueryException;
use Exception;

/**
 * Class DatabaseService represents service which needed for access to database
 *
 * @package App\Service
 */
class DatabaseService
{
    protected ?Connection $readConnection;

    /**
     * Returns read connection
     *
     * @return Connection|null
     * @throws Exception
     */
    public function getReadConnection(): ?Connection
    {
        if (is_null($this->readConnection))
        {
            $this->setReadConnection();
        }

        return $this->readConnection;
    }

    /**
     * Sets connection data from env
     *
     * @throws Exception
     */
    private function setReadConnection(): void
    {
        if ($this->getDriver() === 'pgsql')
        {
            $this->readConnection = new \App\Database\Postgres\Connection();
        }
        else
        {
            throw new Exception('driver not supported');
        }

        $this->readConnection->setHost($_ENV['DATABASE_HOST']);
        $this->readConnection->setPort($_ENV['DATABASE_PORT']);
        $this->readConnection->setDatabase($_ENV['DATABASE_NAME']);
        $this->readConnection->setUser($_ENV['DATABASE_READ_USER']);
        $this->readConnection->setPassword($_ENV['DATABASE_READ_PASSWORD']);
    }

    protected ?Connection $writeConnection;

    /**
     * Returns write connection
     *
     * @return Connection|null
     * @throws Exception
     */
    public function getWriteConnection(): ?Connection
    {
        if (is_null($this->writeConnection))
        {
            $this->setWriteConnection();
        }

        return $this->writeConnection;
    }

    /**
     * Sets connection data from env
     *
     * @throws Exception
     */
    private function setWriteConnection(): void
    {
        if ($this->getDriver() === 'pgsql')
        {
            $this->writeConnection = new \App\Database\Postgres\Connection();
        }
        else
        {
            throw new Exception('driver not supported');
        }

        $this->writeConnection->setHost($_ENV['DATABASE_HOST']);
        $this->writeConnection->setPort($_ENV['DATABASE_PORT']);
        $this->writeConnection->setDatabase($_ENV['DATABASE_NAME']);
        $this->writeConnection->setUser($_ENV['DATABASE_WRITE_USER']);
        $this->writeConnection->setPassword($_ENV['DATABASE_WRITE_PASSWORD']);
    }

    protected ?Connection $rootConnection;

    /**
     * Returns root connection
     *
     * @return Connection|null
     * @throws Exception
     */
    public function getRootConnection(): ?Connection
    {
        if (is_null($this->rootConnection))
        {
            $this->setRootConnection();
        }

        return $this->rootConnection;
    }

    /**
     * Sets connection data from env
     *
     * @throws Exception
     */
    private function setRootConnection(): void
    {
        if ($this->getDriver() === 'pgsql')
        {
            $this->rootConnection = new \App\Database\Postgres\Connection();
        }
        else
        {
            throw new Exception('driver not supported');
        }

        $this->rootConnection->setHost($_ENV['DATABASE_HOST']);
        $this->rootConnection->setPort($_ENV['DATABASE_PORT']);
        $this->rootConnection->setDatabase($_ENV['DATABASE_NAME']);
        $this->rootConnection->setUser($_ENV['DATABASE_ROOT_USER']);
        $this->rootConnection->setPassword($_ENV['DATABASE_ROOT_PASSWORD']);
    }

    protected string $driver;

    /**
     * Returns driver
     *
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * Sets driver
     *
     * @param string $driver
     */
    public function setDriver(string $driver): void
    {
        $this->driver = $driver;
    }

    public function __construct()
    {
        $this->readConnection = null;
        $this->writeConnection = null;
        $this->rootConnection = null;

        $this->setDriver($_ENV['DATABASE_DRIVER']);
    }

    /**
     * Executes query and returns result
     *
     * @param Query $query
     * @return Result
     * @throws QueryException
     */
    public function execute(Query $query): Result
    {
        $query->handleParams();

        $connection = null;

        if ($query->getType() === Connection::Read)
        {
            $connection = $this->getReadConnection();
        }
        elseif ($query->getType() === Connection::Write)
        {
            $connection = $this->getWriteConnection();
        }
        elseif ($query->getType() === Connection::Root)
        {
            $connection = $this->getRootConnection();
        }
        else
        {
            throw new QueryException('Unsupported query type', 500);
        }

        if (!$connection->isConnected())
        {
            $connection->connect();
        }

        try
        {
            return $connection->query(Query::construct($query->getQuery(), $query->getParams()));
        }
        catch (Exception $exception)
        {
            throw new QueryException($exception->getMessage(), 500, $exception);
        }
    }
}