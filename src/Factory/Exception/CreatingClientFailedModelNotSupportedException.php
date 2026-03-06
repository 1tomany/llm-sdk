<?php

namespace OneToMany\LlmSdk\Factory\Exception;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function sprintf;

final class CreatingClientFailedModelNotSupportedException extends InvalidArgumentException
{
    public function __construct(string $model, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Creating a client for the model "%s" failed because there are no registered clients that support it.', $model), previous: $previous);
    }
}
