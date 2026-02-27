<?php

namespace OneToMany\LlmSdk\Client\Gemini\Type\Content;

final readonly class Part
{
    /**
     * @param non-empty-string $text
     */
    public function __construct(public string $text)
    {
    }
}
