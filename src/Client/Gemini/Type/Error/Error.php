<?php

namespace OneToMany\LlmSdk\Client\Gemini\Type\Error;

use function array_filter;
use function explode;
use function implode;

final readonly class Error
{
    public function __construct(
        public int $code,
        public string $message,
        public ?string $status = null,
    ) {
    }

    public function getMessage(): string
    {
        // Removes extra spaces after periods that Gemini likes to add
        return implode(' ', array_filter(explode(' ', $this->message)));
    }
}
