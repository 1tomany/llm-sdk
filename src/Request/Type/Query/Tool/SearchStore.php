<?php

namespace OneToMany\LlmSdk\Request\Type\Query\Tool;

use OneToMany\LlmSdk\Contract\Request\Type\Query\Tool\ToolInterface;

final readonly class SearchStore implements ToolInterface
{
    /**
     * @param non-empty-string $uri
     * @param ?array<string> $filter
     */
    public function __construct(
        private string $uri,
        private ?array $filter = null,
    ) {
    }

    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return ?array<string>
     */
    public function getFilter(): ?array
    {
        return $this->filter;
    }
}
