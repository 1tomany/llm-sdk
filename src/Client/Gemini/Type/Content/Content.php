<?php

namespace App\Prompt\Vendor\Model\Client\Gemini\Type\Content;

final readonly class Content
{
    /**
     * @param list<Part> $parts
     * @param non-empty-string $role
     */
    public function __construct(
        public array $parts,
        public string $role,
    ) {
    }
}
