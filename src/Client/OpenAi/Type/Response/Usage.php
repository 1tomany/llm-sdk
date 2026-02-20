<?php

namespace OneToMany\AI\Clients\Client\OpenAI\Type\Response;

use OneToMany\AI\Clients\Client\OpenAI\Type\Response\Usage\InputTokensDetails;
use OneToMany\AI\Clients\Client\OpenAI\Type\Response\Usage\OutputTokensDetails;
use OneToMany\AI\Clients\Contract\Client\Type\Usage\UsageInterface;

final readonly class Usage implements UsageInterface
{
    /**
     * @param non-negative-int $input_tokens
     * @param non-negative-int $output_tokens
     * @param non-negative-int $total_tokens
     */
    public function __construct(
        public int $input_tokens = 0,
        public InputTokensDetails $input_tokens_details = new InputTokensDetails(),
        public int $output_tokens = 0,
        public OutputTokensDetails $output_tokens_details = new OutputTokensDetails(),
        public int $total_tokens = 0,
    ) {
    }

    /**
     * @see OneToMany\AI\Clients\Contract\Client\Type\Usage\UsageInterface
     */
    public function getInputTokens(): int
    {
        return $this->input_tokens;
    }

    /**
     * @see OneToMany\AI\Clients\Contract\Client\Type\Usage\UsageInterface
     */
    public function getCachedTokens(): int
    {
        return $this->input_tokens_details->cached_tokens;
    }

    /**
     * @see OneToMany\AI\Clients\Contract\Client\Type\Usage\UsageInterface
     */
    public function getOutputTokens(): int
    {
        return $this->output_tokens;
    }
}
