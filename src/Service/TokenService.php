<?php

namespace App\Service;

use App\Exception\InvalidTokenException;
use App\Exception\NeedAuthenticationException;
use App\Helper\Token;
use Exception;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TokenService represents service for tokens
 *
 * @package App\Service
 */
class TokenService
{
    /**
     * Returns token from request
     *
     * @param Request $request
     * @return Token
     * @throws InvalidTokenException
     * @throws NeedAuthenticationException
     */
    public function handle(Request $request): Token
    {
        $token = null;

        if ($request->cookies->has('token'))
        {
            $token = $request->cookies->get('token');
        }
        elseif ($request->headers->has('token'))
        {
            $token = $request->headers->get('token');
        }
        elseif ($request->request->has('token'))
        {
            $token = $request->request->get('token');
        }

        if (is_null($token))
        {
            throw new NeedAuthenticationException('Authentication required', 403);
        }

        try {
            $data = JWT::decode($token, $_ENV['APP_SECRET'], ['HS256']);
        }
        catch (Exception $exception)
        {
            throw new InvalidTokenException($exception->getMessage(), 400, $exception);
        }

        return Token::validate($data);
    }

    /**
     * Creates token string by token class
     *
     * @param Token $token
     * @return string
     */
    public function create(Token $token): string
    {
        return JWT::encode([
            'iat' => $token->getIssuedAt(),
            'nbf' => $token->getNotBefore(),
            'exp' => $token->getExpire(),
            'accId' => $token->getAccountId(),
            'appId' => $token->getApplicationId()
        ], $_ENV['APP_SECRET']);
    }
}