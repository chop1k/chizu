<?php

namespace App\Database;

/**
 * Interface Result represents result interface for query result wrapper
 *
 * @package App\Database
 */
interface Result
{
    /**
     * Returns number of rows
     *
     * @return int
     */
    public function count(): int;

    /**
     * Returns all rows
     *
     * @return array
     */
    public function all(): array;

    /**
     * Returns one row consistently
     *
     * @return array
     */
    public function getRow(): array;
}