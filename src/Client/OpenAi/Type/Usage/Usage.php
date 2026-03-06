<?php

namespace OneToMany\LlmSdk\Client\OpenAi\Type\Usage;

use OneToMany\LlmSdk\Client\OpenAi\Type\Usage\Details\InputTokensDetails;
use OneToMany\LlmSdk\Client\OpenAi\Type\Usage\Details\OutputTokensDetails;

final class Usage
{
    /**
     * @param non-negative-int $input_tokens
     * @param non-negative-int $output_tokens
     * @param non-negative-int $total_tokens
     */
    public function __construct(
        public readonly int $input_tokens = 0,
        public readonly int $output_tokens = 0,
        public readonly int $total_tokens = 0,
        public readonly InputTokensDetails $input_tokens_details = new InputTokensDetails(),
        public readonly OutputTokensDetails $output_tokens_details = new OutputTokensDetails(),
    ) {
    }

    public int $cached_tokens {
        get => $this->input_tokens_details->cached_tokens;
    }
}
