<?php

namespace App\Service;

use App\Exception\ValidationException;
use App\Helper\Validator;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ValidatorService represents service which implements validation logic
 *
 * @package App\Service
 */
class ValidatorService
{
    /**
     * Checks if request need to validate. If need then it will create validator instance and execute validate method
     *
     * @param Request $request
     * @throws ValidationException
     */
    public function validate(Request $request)
    {
        $validator = $request->attributes->get('validator');

        if (!is_string($validator))
        {
            return;
        }

        $validatorInstance = new $validator();

        if (!$validatorInstance instanceof Validator)
        {
            throw new ValidationException('Validator class must be instance of validator interface', 500);
        }

        $validatorInstance->validate($request);
    }
}