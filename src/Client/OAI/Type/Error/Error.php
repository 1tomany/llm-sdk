<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Error;

use OneToMany\LlmSdk\Contract\Client\Type\Error\ErrorInterface;

use function rtrim;

final readonly class Error implements ErrorInterface
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

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Type\Error\ErrorInterface
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Type\Error\ErrorInterface
     */
    public function getInlineMessage(): string
    {
        return rtrim($this->getMessage(), '.');
    }
}
