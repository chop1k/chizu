<?php

namespace App\Database\Postgres;

use App\Database\Result as ResultInterface;
use App\Exception\ResultException;

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
        $result = pg_fetch_all($this->result);

        if ($result === false)
        {
            $error = pg_result_error($this->result);

            if ($error != false)
            {
                throw new ResultException($error, 500);
            }

            return [];
        }

        return $result;
    }

    /**
     * @var int $pointer
     */
    protected int $pointer;

    /**
     * @inheritDoc
     */
    public function hasNext(): bool
    {
        return $this->count() > $this->pointer;
    }

    /**
     * @inheritDoc
     */
    public function getNext(): array
    {
        if (!$this->hasNext())
        {
            throw new ResultException('there is no more rows in result set', 500);
        }

        $row = pg_fetch_assoc($this->result, $this->pointer);

        if ($row === false)
        {
            $error = pg_result_error($this->result);

            if ($error != false)
            {
                throw new ResultException($error, 500);
            }

            throw new ResultException('Unexpected exception', 500);
        }

        $this->pointer++;

        return $row;
    }
}