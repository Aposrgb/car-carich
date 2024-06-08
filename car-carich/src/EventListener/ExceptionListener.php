<?php

namespace App\EventListener;

use App\Helper\Exception\ApiException;
use Doctrine\DBAL\Driver\Exception as TheDriverException;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Twig\Environment;

final readonly class ExceptionListener
{
    public const SERVER_ERROR_MESSAGE = 'Внутренняя ошибка сервера';

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
        $statusCode = $exception->getCode();
        if ($exception instanceof HttpException) {
            $statusCode = $exception->getStatusCode();
        }
        if ($exception instanceof TheDriverException) {
            $message = self::SERVER_ERROR_MESSAGE;
        } else if ($exception instanceof ApiException) {
            $jsonResponse = new JsonResponse(
                data: $exception->getResponseBody()['error'],
                status: $exception->getStatusCode(),
            );
            $event->setResponse($jsonResponse);
            return;
        } else {
            if ($statusCode == Response::HTTP_NOT_FOUND) {
                $message = 'Страница не найдена';
            } else if ($statusCode == Response::HTTP_INTERNAL_SERVER_ERROR) {
                $message = self::SERVER_ERROR_MESSAGE;
            } else if ($statusCode == Response::HTTP_FORBIDDEN) {
                $message = 'Доступ запрещен';
            }
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