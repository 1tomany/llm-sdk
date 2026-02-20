<?php

namespace OneToMany\AI\Clients\Client\OpenAI\Type\Response\Usage;

final readonly class InputTokensDetails
{
    /**
     * @param non-negative-int $cached_tokens
     */
    public function __construct(
        public int $cached_tokens = 0,
    ) {
    }
}
