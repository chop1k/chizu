<?php

namespace App\Helper;

use Symfony\Component\HttpFoundation\Request;

/**
 * Interface Validator represents validation functional
 *
 * @package App\Helper
 */
interface Validator
{
    /**
     * In this method will be validation process.
     * If validation failed method must throw exception, otherwise controller will be executed
     *
     * @param Request $request
     */
    public function validate(Request $request): void;
}