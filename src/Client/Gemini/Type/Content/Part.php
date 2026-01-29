<?php

namespace App\Prompt\Vendor\Model\Client\Gemini\Type\Content;

final readonly class Part
{
    /**
     * @param ?non-empty-string $text
     */
    public function __construct(
        public ?string $text = null,
    ) {
    }
}
