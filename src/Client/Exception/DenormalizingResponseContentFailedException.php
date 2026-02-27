<?php

namespace OneToMany\LlmSdk\Client\Exception;

use OneToMany\LlmSdk\Exception\RuntimeException;

final class DenormalizingResponseContentFailedException extends RuntimeException
{
    public function __construct(\Throwable $previous)
    {
        parent::__construct($previous->getMessage(), previous: $previous);
    }
}
