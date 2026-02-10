<?php

namespace OneToMany\AI\Response\Query;

final readonly class UsageResponse
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
}
