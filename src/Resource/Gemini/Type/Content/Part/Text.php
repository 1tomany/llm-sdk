<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content\Part;

final readonly class Text
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
            'text' => $this->text,
        ];
    }
}
