<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Usage\Details;

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
