<?php

namespace OneToMany\AI\Client\Exception;

use OneToMany\AI\Exception\RuntimeException;

use function parse_url;
use function rtrim;
use function sprintf;

use const PHP_URL_HOST;

final class ConnectingToHostFailedException extends RuntimeException
{
    public function __construct(string $url, \Throwable $previous)
    {
        parent::__construct(sprintf('Connecting to the host "%s" failed: %s.', parse_url($url, PHP_URL_HOST) ?: $url, rtrim($previous->getMessage(), '.')), previous: $previous);
    }
}
