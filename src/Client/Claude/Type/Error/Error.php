<?php

namespace OneToMany\AI\Client\Claude\Type\Error;

use OneToMany\AI\Contract\Client\Type\Error\ErrorInterface;

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
     * @see OneToMany\AI\Contract\Client\Type\Error\ErrorInterface
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @see OneToMany\AI\Contract\Client\Type\Error\ErrorInterface
     */
    public function getInlineMessage(): string
    {
        return rtrim($this->message, '.');
    }
}
