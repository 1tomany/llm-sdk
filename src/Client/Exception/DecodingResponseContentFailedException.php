<?php

namespace OneToMany\LlmSdk\Client\Exception;

use OneToMany\LlmSdk\Exception\RuntimeException;

use function rtrim;
use function sprintf;

final class DecodingResponseContentFailedException extends RuntimeException
{
    public function __construct(string $requestType, string $model, \Throwable $previous)
    {
        parent::__construct(sprintf('Decoding the response from the "%s(%s)" request failed: %s.', $requestType, $model, rtrim($previous->getMessage(), '.')), previous: $previous);
    }
}
