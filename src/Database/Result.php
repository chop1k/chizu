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
     * Returns true if last taken row is not last in result set
     *
     * @return bool
     */
    public function hasNext(): bool;

    /**
     * Returns next row in result set
     *
     * @return array
     */
    public function getNext(): array;
}