<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class TokenController represents controller for tokens
 *
 * @package App\Controller
 */
class TokenController extends AbstractController
{
    /**
     * Action for creating tokens
     *
     * @return Response
     */
    public function create()
    {
        return new Response('ok', 200);
    }
}