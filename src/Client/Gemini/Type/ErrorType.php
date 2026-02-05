<?php

namespace OneToMany\AI\Client\Gemini\Type;

use function array_filter;
use function explode;
use function implode;
use function rtrim;

final readonly class ErrorType
{
    public function __construct(
        public int $code,
        public string $message,
        public ?string $status = null,
    ) {
    }

    /**
     * Removes extra spaces after periods that Gemini adds.
     */
    public function getMessage(): string
    {
        return implode(' ', array_filter(explode(' ', $this->message)));
    }

    /**
     * Removes any trailing periods so this message
     * can be used in the message of another exception.
     */
    public function getInlineMessage(): string
    {
        return rtrim($this->getMessage(), '.');
    }
}
