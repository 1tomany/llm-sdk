<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Batch;

final readonly class Batch
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public string $name,
        public Metadata $metadata,
        public bool $done = false,
        public ?Response $response = null,
    ) {
    }
}
