<?php

namespace OneToMany\LlmSdk\Response\Usage;

use OneToMany\LlmSdk\Contract\Response\Usage\TokenUsageInterface;

/**
 * @phpstan-type SerializedTokenUsage array{
 *   inputTokens: non-negative-int,
 *   cachedTokens: non-negative-int,
 *   outputTokens: non-negative-int,
 *   totalTokens: non-negative-int,
 * }
 */
final readonly class TokenUsage implements \JsonSerializable, TokenUsageInterface
{
    /**
     * @param non-negative-int $inputTokens
     * @param non-negative-int $cachedTokens
     * @param non-negative-int $outputTokens
     */
    public function __construct(
        private int $inputTokens = 0,
        private int $cachedTokens = 0,
        private int $outputTokens = 0,
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Response\Usage\TokenUsageInterface
     */
    public function getInputTokens(): int
    {
        return $this->inputTokens;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Response\Usage\TokenUsageInterface
     */
    public function getCachedTokens(): int
    {
        return $this->cachedTokens;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Response\Usage\TokenUsageInterface
     */
    public function getOutputTokens(): int
    {
        return $this->outputTokens;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Response\Usage\TokenUsageInterface
     */
    public function getTotalTokens(): int
    {
        return $this->inputTokens + $this->cachedTokens + $this->outputTokens;
    }

    /**
     * @see \JsonSerializable
     *
     * @return SerializedTokenUsage
     */
    public function jsonSerialize(): array
    {
        return [
            'inputTokens' => $this->getInputTokens(),
            'cachedTokens' => $this->getCachedTokens(),
            'outputTokens' => $this->getOutputTokens(),
            'totalTokens' => $this->getTotalTokens(),
        ];
    }
}
