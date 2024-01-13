<?php

namespace App\EventListener;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig\Environment;

final readonly class ExceptionListener
{
    public function __construct(
        protected LoggerInterface $logger,
        protected Environment     $twig,
    )
    {
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $this->logger->error($exception->getMessage(), $exception->getTrace());
        $message = $exception->getMessage();
        $statusCode = $exception->getStatusCode();
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
        }
        if ($statusCode == Response::HTTP_NOT_FOUND) {
            $message = 'Страница не найдена';
        } else if ($statusCode == Response::HTTP_INTERNAL_SERVER_ERROR) {
            $message = 'Внутренняя ошибка сервера';
        } else if ($statusCode == Response::HTTP_FORBIDDEN) {
            $message = 'Доступ запрещен';
        }
        $response = new Response();
        $event->setResponse($response
            ->setContent(
                $this->twig->render(
                    'exception/exception.html.twig',
                    ['message' => $message, 'code' => $statusCode])
            ));
    }
}