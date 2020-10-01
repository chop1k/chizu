<?php

namespace App\Subscriber;

use App\Exception\ContentTypeException;
use App\Exception\InvalidTokenException;
use App\Exception\NeedAuthenticationException;
use App\Exception\ParseException;
use App\Service\TokenService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;

/**
 * Class RequestSubscriber represents request event subscriber
 *
 * @package App\Subscriber
 */
class RequestSubscriber implements EventSubscriberInterface
{
    private TokenService $token;

    public function __construct(TokenService $token)
    {
        $this->token = $token;
    }

    /**
     * Request event subscriber
     *
     * @param RequestEvent $event
     * @throws ContentTypeException
     * @throws ParseException
     * @throws InvalidTokenException
     * @throws NeedAuthenticationException
     */
    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($request->isMethod('POST'))
        {
            $this->parse($request);
        }

        if ($request->attributes->get('authentication') !== false)
        {
            $request->attributes->set('token', $this->token->handle($request));
        }
    }

    /**
     * Private function which parses json
     *
     * @param Request $request
     * @throws ContentTypeException
     * @throws ParseException
     */
    private function parse(Request $request)
    {
        if ($request->headers->get('content-type') !== 'application/json')
        {
            throw new ContentTypeException('Content type must be application/json', 415);
        }

        $json = json_decode($request->getContent());

        if (is_null($json))
        {
            throw new ParseException('Cannot parse request body', 400);
        }

        $request->request->replace((array) $json);
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            RequestEvent::class => 'onKernelRequest'
        ];
    }
}