<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Error;

final readonly class Error
{
    /**
     * @param ?non-empty-string $type
     * @param ?non-empty-string $param
     * @param ?non-empty-string $code
     */
    public function __construct(
        public string $message,
        public ?string $type = null,
        public ?string $param = null,
        public ?string $code = null,
    ) {
    }
}
