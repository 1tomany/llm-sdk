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
     * @return non-negative-int
     */
    public function getInputTokens(): int
    {
        return $this->inputTokens;
    }

    /**
     * @return non-negative-int
     */
    public function getCachedTokens(): int
    {
        return $this->cachedTokens;
    }

    /**
     * @return non-negative-int
     */
    public function getOutputTokens(): int
    {
        return $this->outputTokens;
    }

    /**
     * @return non-negative-int
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
