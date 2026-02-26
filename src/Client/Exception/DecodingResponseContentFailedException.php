<?php

namespace OneToMany\LlmSdk\Client\Exception;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\BaseRequest;

use function rtrim;
use function sprintf;

final class DecodingResponseContentFailedException extends RuntimeException
{
    public function __construct(BaseRequest $request, \Throwable $previous)
    {
        parent::__construct(sprintf('Decoding the "%s" response from the model "%s" failed: %s.', $request->getRequestType(), $request->getModel(), rtrim($previous->getMessage(), '.')), previous: $previous);
    }
}
