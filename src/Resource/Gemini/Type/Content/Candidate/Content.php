<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content\Candidate;

use OneToMany\LlmSdk\Resource\Gemini\Type\Content\Candidate\Part\TextPart;

final readonly class Content
{
    /**
     * @param list<TextPart> $parts
     */
    public function __construct(
        public array $parts,
        public string $role,
    ) {
    }
}
