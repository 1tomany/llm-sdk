<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Batch;

final readonly class Output
{
    public function __construct(public string $responsesFile)
    {
    }
}
