<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\Document;

final readonly class Document
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
    ) {
    }
}
