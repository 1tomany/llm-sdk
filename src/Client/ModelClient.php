<?php

namespace OneToMany\AI\Client;

use OneToMany\AI\Contract\Client\Type\Error\ErrorTypeInterface;
use OneToMany\AI\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface as HttpClientHttpExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

abstract readonly class ModelClient
{
    abstract protected function decodeErrorResponse(ResponseInterface $response): ErrorTypeInterface;

    /**
     * Decodes, wraps, and throws any exception thrown by the Symfony HTTP Client.
     *
     * @throws RuntimeException
     */
    protected function handleHttpException(HttpClientExceptionInterface $exception): never
    {
        if ($exception instanceof HttpClientHttpExceptionInterface) {
            throw new RuntimeException($this->decodeErrorResponse($exception->getResponse())->getMessage(), $exception->getResponse()->getStatusCode(), $exception);
        }

        throw new RuntimeException($exception->getMessage(), previous: $exception);
    }
}
