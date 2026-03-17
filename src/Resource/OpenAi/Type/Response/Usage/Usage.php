<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Usage;

use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Usage\Details\InputTokensDetails;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Usage\Details\OutputTokensDetails;

final readonly class Usage
{
    /**
     * @param non-negative-int $input_tokens
     * @param non-negative-int $prompt_tokens
     * @param non-negative-int $output_tokens
     * @param non-negative-int $total_tokens
     */
    public function __construct(
        public int $input_tokens = 0,
        public int $prompt_tokens = 0,
        public int $output_tokens = 0,
        public int $total_tokens = 0,
        public InputTokensDetails $input_tokens_details = new InputTokensDetails(),
        public OutputTokensDetails $output_tokens_details = new OutputTokensDetails(),
    ) {
    }

    /**
     * @return non-negative-int
     */
    public function getInputTokens(): int
    {
        return $this->input_tokens + $this->prompt_tokens;
    }

    /**
     * @return non-negative-int
     */
    public function getCachedTokens(): int
    {
        return $this->input_tokens_details->cached_tokens;
    }

    /**
     * @return non-negative-int
     */
    public function getOutputTokens(): int
    {
        return $this->output_tokens;
    }
}
