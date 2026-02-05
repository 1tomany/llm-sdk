<?php

namespace OneToMany\AI\Client\Gemini\Type\Error;

use OneToMany\AI\Contract\Client\Type\Error\ErrorTypeInterface;

use function array_filter;
use function explode;
use function implode;
use function rtrim;

final readonly class ErrorType implements ErrorTypeInterface
{
    public function __construct(
        public int $code,
        public string $message,
        public ?string $status = null,
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Client\Type\Error\ErrorTypeInterface
     */
    public function getMessage(): string
    {
        // Removes extra spaces after periods that Gemini adds.
        return implode(' ', array_filter(explode(' ', $this->message)));
    }

    /**
     * @see OneToMany\AI\Contract\Client\Type\Error\ErrorTypeInterface
     */
    public function getInlineMessage(): string
    {
        return rtrim($this->getMessage(), '.');
    }
}
