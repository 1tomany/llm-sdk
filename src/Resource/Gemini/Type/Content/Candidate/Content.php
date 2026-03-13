<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content\Candidate;

use OneToMany\LlmSdk\Resource\Gemini\Type\Content\Candidate\Part\Part;

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
