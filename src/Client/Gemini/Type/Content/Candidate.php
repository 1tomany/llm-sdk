<?php

namespace OneToMany\LlmSdk\Client\Gemini\Type\Content;

final readonly class Candidate
{
    /**
     * @param non-empty-string $finishReason
     */
    public function __construct(
        public Content $content,
        public string $finishReason,
    )
    {
    }
}
