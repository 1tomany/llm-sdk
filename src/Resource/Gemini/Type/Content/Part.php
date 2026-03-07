<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content;

final readonly class Part
{
    /**
     * @param non-empty-string $text
     */
    public function __construct(public string $text)
    {
    }
}
