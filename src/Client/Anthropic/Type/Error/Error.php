<?php

namespace OneToMany\LlmSdk\Client\Anthropic\Type\Error;

use OneToMany\LlmSdk\Contract\Client\Type\Error\ErrorInterface;

use function rtrim;

final readonly class Error implements ErrorInterface
{
    /**
     * @param non-empty-string $type
     * @param non-empty-string $message
     *
     * @return void
     */
    public function __construct(
        public string $type,
        public string $message,
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
        return rtrim($this->message, '.');
    }
}
