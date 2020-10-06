<?php

namespace App\Service;

use App\Database\Connection;
use App\Database\Postgres\Connection as PostgreConnection;
use App\Database\Query;
use App\Database\Result;
use App\Exception\BaseException;
use App\Exception\QueryException;
use Exception;

/**
 * Class DatabaseService represents service which needed for access to database
 *
 * @package App\Service
 */
class DatabaseService
{
    protected array $connections;

    public function getConnection(int $type): ?Connection
    {
        if (!isset($this->connections[$type]))
        {
            return null;
        }

        return $this->connections[$type];
    }

    protected function setConnection(int $type, ?Connection $connection): void
    {
        $this->connections[$type] = $connection;
    }

    public function __construct()
    {
        $this->connections = [
            Connection::Read => null,
            Connection::Insert => null,
            Connection::Update => null,
            Connection::Root => null
        ];
    }

    /**
     * Executes query and returns result
     *
     * @param Query $query
     * @return Result
     * @throws QueryException|BaseException
     */
    public function execute(Query $query): Result
    {
        $query->handleParams();

        $type = $query->getType();

        $connection = $this->getConnection($type);

        if (is_null($connection))
        {
            $connection = PostgreConnection::fromEnv($type);

            $this->setConnection($type, $connection);
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