<?php

namespace OneToMany\LlmSdk\Contract\Client\Request;

use OneToMany\LlmSdk\Contract\Client\Enum\HttpMethod;
use OneToMany\LlmSdk\Contract\Client\Response\ClientResponseInterface;

/**
 * @template R of ClientResponseInterface
 */
interface ClientRequestInterface
{
    public HttpMethod $method { get; }

    /**
     * @var non-empty-string
     */
    public string $url { get; }

    /**
     * @var class-string<R>
     */
    public string $responseType { get; }

    /**
     * @return ?array<non-empty-string, mixed>
     */
    public function getBody(): ?array;

    /**
     * @return ?array<non-empty-string, mixed>
     */
    public function getJson(): ?array;
}
