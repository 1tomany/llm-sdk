<?php

namespace OneToMany\AI\Clients\Client\OpenAI\Type\Error;

use OneToMany\AI\Clients\Contract\Client\Type\Error\ErrorInterface;

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
     * @see OneToMany\AI\Clients\Contract\Client\Type\Error\ErrorInterface
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @see OneToMany\AI\Clients\Contract\Client\Type\Error\ErrorInterface
     */
    public function getInlineMessage(): string
    {
        return rtrim($this->getMessage(), '.');
    }
}
