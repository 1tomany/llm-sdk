<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Usage\Details;

final readonly class OutputTokensDetails
{
    /**
     * @param non-negative-int $reasoning_tokens
     */
    public function __construct(
        public int $reasoning_tokens = 0,
    ) {
    }
}
