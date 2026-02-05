<?php

namespace OneToMany\AI\Client;

use Symfony\Contracts\HttpClient\ResponseInterface;

abstract readonly class ModelClient
{
    abstract protected function decodeErrorResponse(ResponseInterface $response): object;
}
