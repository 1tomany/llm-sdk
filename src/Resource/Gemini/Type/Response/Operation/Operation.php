<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Response\Operation;

readonly class Operation
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
