<?php

namespace App\Controller;

use App\Helper\Token;
use App\Service\DatabaseService;
use App\Service\TokenService;
use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TokenController represents controller for tokens
 *
 * @package App\Controller
 */
class TokenController extends AbstractController
{
    /**
     * Contains database service
     *
     * @var DatabaseService $db
     */
    private DatabaseService $db;

    public function __construct(DatabaseService $databaseService)
    {
        $this->db = $databaseService;
    }

    /**
     * Action for creating tokens
     *
     * @param TokenService $tokenService
     * @param Request $request
     * @return Response
     */
    public function create(TokenService $tokenService, Request $request): Response
    {
        $token = new Token();

        $time = Carbon::now();

        $token->setIssuedAt($time->unix());
        $token->setNotBefore($time->unix());
        $token->setExpire($time->addDays(7)->unix());

        $token->setAccountId(0); // TODO: finish this
        $token->setApplicationId($request->request->get('application_id'));

        return $this->json([
            'token' => $tokenService->create($token)
        ]);
    }

    /**
     * Identifies user by token and return user data
     *
     * @return Response
     */
    public function identify(): Response
    {
        return new Response('ok', 200);
    }
}