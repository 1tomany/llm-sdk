<?php

namespace OneToMany\LlmSdk\Client\Gemini\Type\Content;

final readonly class Content
{
    /**
     * @param non-empty-list<Part> $parts
     */
    public function __construct(
        public array $parts,
        public string $role,
    )
    {
    }
}
