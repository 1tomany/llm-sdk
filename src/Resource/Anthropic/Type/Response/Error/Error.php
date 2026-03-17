<?php

namespace OneToMany\LlmSdk\Resource\Anthropic\Type\Response\Error;

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
