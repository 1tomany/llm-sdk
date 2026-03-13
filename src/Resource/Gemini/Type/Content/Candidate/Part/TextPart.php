<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content\Candidate\Part;

final readonly class TextPart
{
    /**
     * @param non-empty-string $text
     */
    public function __construct(public string $text)
    {
    }
}
