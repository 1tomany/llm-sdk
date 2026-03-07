<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content;

final readonly class Content
{
    /**
     * @param list<Part> $parts
     */
    public function __construct(
        public array $parts,
        public string $role,
    ) {
    }
}
