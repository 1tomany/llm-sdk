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

    /**
     * @return array{
     *   text: non-empty-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'text' => $this->text
        ];
    }
}
