<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Batch;

final readonly class Response
{
    /**
     * @param non-empty-string $responsesFile
     */
    public function __construct(
        public string $responsesFile,
    ) {
    }
}
