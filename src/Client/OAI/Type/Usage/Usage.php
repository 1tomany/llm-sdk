<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Usage;

use OneToMany\LlmSdk\Client\OpenAI\Type\Usage\Details\InputTokensDetails;
use OneToMany\LlmSdk\Client\OpenAI\Type\Usage\Details\OutputTokensDetails;
use OneToMany\LlmSdk\Contract\Client\Type\Usage\UsageInterface;
use OneToMany\LlmSdk\Response\Query\UsageResponse;

final readonly class Usage implements UsageInterface
{
    /**
     * @param non-negative-int $input_tokens
     * @param non-negative-int $output_tokens
     * @param non-negative-int $total_tokens
     */
    public function __construct(
        public int $input_tokens = 0,
        public int $output_tokens = 0,
        public int $total_tokens = 0,
        public InputTokensDetails $input_tokens_details = new InputTokensDetails(),
        public OutputTokensDetails $output_tokens_details = new OutputTokensDetails(),
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Type\Usage\UsageInterface
     */
    public function getInputTokens(): int
    {
        return $this->input_tokens;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Type\Usage\UsageInterface
     */
    public function getCachedTokens(): int
    {
        return $this->input_tokens_details->cached_tokens;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Type\Usage\UsageInterface
     */
    public function getOutputTokens(): int
    {
        return $this->output_tokens;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Type\Usage\UsageInterface
     */
    public function toResponse(): UsageResponse
    {
        return new UsageResponse($this->input_tokens, $this->getCachedTokens(), $this->output_tokens);
    }
}
