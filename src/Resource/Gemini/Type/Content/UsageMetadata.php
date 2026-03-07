<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content;

final class UsageMetadata
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
        public readonly int $promptTokenCount = 0,
        public readonly int $cachedContentTokenCount = 0,
        public readonly int $candidatesTokenCount = 0,
        public readonly int $toolUsePromptTokenCount = 0,
        public readonly int $thoughtsTokenCount = 0,
        public readonly int $totalTokenCount = 0,
    ) {
    }

    /**
     * @var non-negative-int
     */
    public int $outputTokenCount {
        get => $this->candidatesTokenCount + $this->toolUsePromptTokenCount + $this->thoughtsTokenCount;
    }
}
