<?php

namespace OneToMany\LlmSdk\Client\Exception;

use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface as HttpClientHttpExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpClientResponseInterface;

class HttpTransportException extends RuntimeException
{
    public function __construct(HttpClientResponseInterface $response, \Throwable $previous)
    {
        /** @var non-empty-string $url */
        $url = $response->getInfo('url');

        // if (!is_string($url) || !$url) {
        //     $url = $request->url;
        // }

        if ($previous instanceof HttpClientHttpExceptionInterface) {
            $statusCode = $previous->getResponse()->getStatusCode();
        }

        throw new RuntimeException(\sprintf('The HTTP request to "%s" failed.', $url), $statusCode ?? 0, $previous);
    }
}
