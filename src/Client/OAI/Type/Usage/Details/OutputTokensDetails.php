<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Usage\Details;

final readonly class OutputTokensDetails
{
    /**
     * @param non-negative-int $reasoning_tokens
     */
    public function __construct(public int $reasoning_tokens = 0)
    {
    }
}
