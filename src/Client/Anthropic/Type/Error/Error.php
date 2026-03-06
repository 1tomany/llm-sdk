<?php

namespace OneToMany\LlmSdk\Client\Anthropic\Type\Error;

final readonly class Error
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $message
     */
    public function __construct(
        public string $type,
        public string $message,
    ) {
    }
}
