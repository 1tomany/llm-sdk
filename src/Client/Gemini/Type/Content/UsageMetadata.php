<?php

namespace App\Prompt\Vendor\Model\Client\Gemini\Type\Content;

final readonly class UsageMetadata
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
}
