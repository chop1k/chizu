<?php

namespace App\Database\Postgres;

use App\Database\Result as ResultInterface;

/**
 * Class Result represents result wrapper for postgresql
 *
 * @package App\Database\Postgres
 */
class Result implements ResultInterface
{
    public function __construct($result)
    {
        $this->result = $result;

        $this->pointer = 0;
    }

    /**
     * @var resource $result
     */
    protected $result;

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return pg_affected_rows($this->result);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return pg_fetch_all($this->result);
    }

    /**
     * @var int $pointer
     */
    protected int $pointer;

    /**
     * @inheritDoc
     */
    public function getRow(): array
    {
        return pg_fetch_assoc($this->result);
    }
}