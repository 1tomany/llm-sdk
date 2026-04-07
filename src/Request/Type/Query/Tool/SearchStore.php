<?php

namespace OneToMany\LlmSdk\Request\Type\Query\Tool;

use OneToMany\LlmSdk\Contract\Request\Type\Query\Tool\ToolInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;

final readonly class SearchStore implements ToolInterface
{
    /**
     * @param non-empty-string $uri
     */
    public function __construct(
        private string $uri,
    ) {
    }

    /**
     * @throws InvalidArgumentException when the trimmed search store URI is empty
     */
    public static function create(
        string|self|null $uri,
    ): self {
        if (!$uri instanceof self) {
            if (!$uri = \trim((string) $uri)) {
                throw new InvalidArgumentException('The search store URI cannot be empty.');
            }

            $uri = new self($uri);
        }

        return $uri;
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }
}
