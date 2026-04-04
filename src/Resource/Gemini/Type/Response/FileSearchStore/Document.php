<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore;

final readonly class Document
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
        public bool $done = false,
    ) {
    }
}
