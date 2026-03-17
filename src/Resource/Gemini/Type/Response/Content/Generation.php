<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Response\Content;

use OneToMany\LlmSdk\Resource\Gemini\Type\Response\Content\Candidate\Candidate;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\Usage\UsageMetadata;

use function array_map;
use function implode;
use function trim;

final readonly class Generation
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
     * @return ?non-empty-string
     */
    public function getOutput(): ?string
    {
        return trim(implode('', array_map(fn ($c) => $c->getOutput(), $this->candidates))) ?: null;
    }
}
