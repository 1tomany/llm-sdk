<?php

namespace OneToMany\LlmSdk\Client\Gemini\Type\Content;

use OneToMany\LlmSdk\Exception\RuntimeException;

use function array_map;
use function implode;
use function sprintf;
use function trim;

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
        $output = array_map(fn ($c) => $c->getOutput(), $this->candidates);

        return trim(implode('', $output)) ?: throw new RuntimeException(sprintf('The model "%s" failed to generate any output.', $this->modelVersion));
    }
}
