<?php

namespace OneToMany\AI\Client\Exception;

use OneToMany\AI\Exception\RuntimeException;

use function rtrim;
use function sprintf;
use function ucfirst;

class DecodingResponseContentFailedException extends RuntimeException
{
    public function __construct(string $message, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('%s failed because the response could not be decoded.', ucfirst(rtrim($message, '.'))), previous: $previous);
    }
}
