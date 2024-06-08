<?php

namespace App\Helper\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    protected $message;

    protected ?string $detail;

    protected int $status;

    protected array $validationError;

    public function __construct(
        ?string       $message = null,
        ?string       $detail = null,
        array         $validationError = ['query' => [], 'body' => []],
        int           $status = Response::HTTP_BAD_REQUEST,
        HttpException $previous = null,
        array         $headers = [],
        ?int          $code = 0,
    )
    {
        $this->message = is_null($message) ? Response::$statusTexts[$status] : $message;
        $this->detail = $detail;
        $this->status = $status;
        $this->validationError = $validationError;

        parent::__construct($status, $message, $previous, $headers, $code);
    }

    public function getResponseBody(): array
    {
        return [
            'error' => [
                'status' => $this->status,
                'message' => $this->message,
                'detail' => $this->detail,
                'validationError' => $this->validationError,
            ],
        ];
    }
}
