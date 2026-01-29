<?php

namespace App\Prompt\Vendor\Model\Client\Gemini\Type\Content;

final readonly class GenerateContentResponse
{
    /**
     * @param list<Candidate> $candidates
     * @param non-empty-string $modelVersion
     * @param non-empty-string $responseId
     */
    public function __construct(
        public array $candidates,
        public UsageMetadata $usageMetadata,
        public string $modelVersion,
        public string $responseId,
    ) {
    }

    /**
     * @return ?non-empty-string
     */
    public function getOutput(): ?string
    {
        if (isset($this->candidates[0]->content->parts[0])) {
            if ($this->candidates[0]->finishReason?->isStop()) {
                return $this->candidates[0]->content->parts[0]->text;
            }
        }

        return null;
    }
}
