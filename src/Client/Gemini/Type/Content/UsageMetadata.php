<?php

namespace OneToMany\AI\Client\Gemini\Type\Content;

use OneToMany\AI\Contract\Client\Type\Usage\UsageInterface;

final readonly class UsageMetadata implements UsageInterface
{
    /**
     * @param non-negative-int $promptTokenCount
     * @param non-negative-int $cachedContentTokenCount
     * @param non-negative-int $candidatesTokenCount
     * @param non-negative-int $toolUsePromptTokenCount
     * @param non-negative-int $thoughtsTokenCount
     * @param non-negative-int $totalTokenCount
     */
    public function __construct(
        public int $promptTokenCount = 0,
        public int $cachedContentTokenCount = 0,
        public int $candidatesTokenCount = 0,
        public int $toolUsePromptTokenCount = 0,
        public int $thoughtsTokenCount = 0,
        public int $totalTokenCount = 0,
    ) {
    }

    /**
     * @see OneToMany\AI\Contract\Client\Type\Usage\UsageInterface
     */
    public function getInputTokens(): int
    {
        return $this->promptTokenCount;
    }

    /**
     * @see OneToMany\AI\Contract\Client\Type\Usage\UsageInterface
     */
    public function getCachedTokens(): int
    {
        return $this->cachedContentTokenCount;
    }

    /**
     * @see OneToMany\AI\Contract\Client\Type\Usage\UsageInterface
     */
    public function getOutputTokens(): int
    {
        return $this->candidatesTokenCount + $this->toolUsePromptTokenCount + $this->thoughtsTokenCount;
    }
}
