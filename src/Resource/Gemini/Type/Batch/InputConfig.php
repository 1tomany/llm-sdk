<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Batch;

final readonly class InputConfig
{
    /**
     * @param non-empty-string $fileName
     */
    public function __construct(
        public string $fileName,
    ) {
    }
}
