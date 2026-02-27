<?php

namespace OneToMany\LlmSdk\Client\Gemini\Type\Batch;

final readonly class Response
{
    /**
     * @param non-empty-string $responsesFile
     */
    public function __construct(public string $responsesFile)
    {
    }
}
