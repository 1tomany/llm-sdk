<?php

namespace OneToMany\LlmSdk\Client\Gemini\Type\Content;

use OneToMany\LlmSdk\Client\Gemini\Type\Content\Candidate;
use OneToMany\LlmSdk\Exception\RuntimeException;

final readonly class GenerateContentResponse
{
    /**
     * @param list<Candidate> $candidates
     * @param non-empty-lowercase-string $modelVersion
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
     * @return non-empty-string
     */
    public function getOutput(): string
    {
        $output = null;

        if ([] !== $this->candidates) {
            print_r($this->candidates[0]->content->parts[0]); exit;

            $output = $this->candidates[0]->content->parts[0]->text;
        }

        return \trim($output ?? '') ?: throw new RuntimeException(\sprintf('The model "%s" failed to generate any output.', $this->modelVersion));
    }
}
