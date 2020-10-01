<?php

namespace App\Service;

use App\Exception\BaseException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ExceptionService represents service for exceptions
 *
 * @package App\Service
 */
class ExceptionService
{
    /**
     * Returns response by exception
     *
     * @param BaseException $exception
     * @return Response
     */
    public function handle(BaseException $exception): Response
    {
        return new Response($exception->toJSON(), $exception->getCode(), [
            'content-type' => 'application/json'
        ]);
    }
}