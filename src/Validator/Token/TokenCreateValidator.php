<?php

namespace App\Validator\Token;

use App\Exception\ValidationException;
use App\Helper\Validator;
use Symfony\Component\HttpFoundation\Request;

class TokenCreateValidator implements Validator
{
    /**
     * @inheritDoc
     */
    public function validate(Request $request): void
    {
        if (!$request->request->has('name'))
        {
            throw new ValidationException('Name is required', 400);
        }

        if (!$request->request->has('password'))
        {
            throw new ValidationException('Password is required', 400);
        }

        if (!$request->request->has('application_id'))
        {
            throw new ValidationException('Application id is required', 400);
        }
    }
}