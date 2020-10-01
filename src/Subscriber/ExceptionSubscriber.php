<?php

namespace App\Subscriber;

use App\Exception\BaseException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ExceptionSubscriber represents exception event subscriber
 *
 * @package App\Subscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * Exception event handler
     *
     * @param ExceptionEvent $event
     */
    public function onKernelException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();

        $response = new Response();

        $response->headers->set('content-type', 'application/json');

        if ($exception instanceof BaseException)
        {
            $response->setContent($exception->toJSON());
            $response->setStatusCode($exception->getCode());
        }
        else
        {
            $response->setContent(json_encode([
                'message' => $exception->getMessage(),
                'code' => $exception->getTrace()
            ]));

            if ($exception instanceof NotFoundHttpException)
                $response->setStatusCode(404);
            else
                $response->setStatusCode(500);
        }

        $event->setResponse($response);
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents()
    {
        return [
            ExceptionEvent::class => 'onKernelException'
        ];
    }
}