<?php

namespace App\Database\Postgres;

use App\Database\Connection as ConnectionInterface;
use App\Exception\BaseException;
use App\Exception\QueryException;

/**
 * Class Connection represents connection wrapper for postgresql
 *
 * @package App\Database\Postgres
 */
class Connection implements ConnectionInterface
{
    /**
     * Contains raw postgres connection or null
     *
     * @var resource|null $connection
     */
    protected $connection;

    /**
     * @inheritDoc
     */
    public function getRaw()
    {
        return $this->connection;
    }

    /**
     * @inheritDoc
     */
    public function connect(): void
    {
        $this->connection = pg_connect(
            "user={$this->getUser()} 
            dbname={$this->getDatabase()} 
            host={$this->getHost()} 
            port={$this->getPort()} 
            password={$this->getPassword()}"
        );

        if ($this->connection === false)
        {
            throw new BaseException(pg_last_error($this->connection), 500);
        }

        $this->connected = true;
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        if ($this->isConnected())
        {
            pg_close($this->connection);
        }
    }

    /**
     * @inheritDoc
     */
    public function query(string $query): Result
    {
        if (!$this->isConnected())
        {
            throw new BaseException('Connection not established', 500);
        }

        $res = pg_query($this->connection, $query);

        if ($res === false)
        {
            throw new QueryException(pg_last_error($this->connection), 500);
        }

        return new Result($res);
    }

    public function __construct()
    {
        $this->connection = null;
        $this->connected = false;
    }

    /**
     * @var bool $connected
     */
    protected bool $connected;

    /**
     * @inheritDoc
     */
    public function isConnected()
    {
        return $this->connected;
    }

    /**
     * Contains connection host
     *
     * @var string $host
     */
    protected string $host;

    /**
     * @inheritDoc
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @inheritDoc
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * Contains connection port
     *
     * @var int $port
     */
    protected int $port;

    /**
     * @inheritDoc
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @inheritDoc
     */
    public function setPort(int $port): void
    {
        $this->port = $port;
    }

    /**
     * Contains database name
     *
     * @var string $database
     */
    protected string $database;

    /**
     * @inheritDoc
     */
    public function getDatabase(): string
    {
        return $this->database;
    }

    /**
     * @inheritDoc
     */
    public function setDatabase(string $name): void
    {
        $this->database = $name;
    }

    /**
     * Contains connection password
     *
     * @var string $password
     */
    protected string $password;

    /**
     * @inheritDoc
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @inheritDoc
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Contains connection user
     *
     * @var string $user
     */
    protected string $user;

    /**
     * @inheritDoc
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function setUser(string $name): void
    {
        $this->user = $name;
    }

    /**
     * Shortcut for creating connection instance by environment
     *
     * @param int $type
     * @return Connection
     * @throws BaseException
     */
    public static function fromEnv(int $type): Connection
    {
        $connection = new Connection();

        $connection->setHost($_ENV['DATABASE_HOST']);
        $connection->setPort($_ENV['DATABASE_PORT']);
        $connection->setDatabase($_ENV['DATABASE_NAME']);

        if ($type === ConnectionInterface::Read)
        {
            $connection->setUser($_ENV['DATABASE_READ_USER']);
            $connection->setPassword($_ENV['DATABASE_READ_PASSWORD']);
        }
        elseif ($type === ConnectionInterface::Insert)
        {
            $connection->setUser($_ENV['DATABASE_INSERT_USER']);
            $connection->setPassword($_ENV['DATABASE_INSERT_PASSWORD']);
        }
        elseif ($type === ConnectionInterface::Update)
        {
            $connection->setUser($_ENV['DATABASE_UPDATE_USER']);
            $connection->setPassword($_ENV['DATABASE_UPDATE_PASSWORD']);
        }
        elseif ($type === ConnectionInterface::Root)
        {
            $connection->setUser($_ENV['DATABASE_ROOT_USER']);
            $connection->setPassword($_ENV['DATABASE_ROOT_PASSWORD']);
        }
        else
        {
            throw new BaseException('Unsupported connection type', 500);
        }

        return $connection;
    }
}