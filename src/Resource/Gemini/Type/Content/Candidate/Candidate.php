<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Content\Candidate;

use function array_map;
use function implode;
use function trim;

final readonly class Candidate
{
    /**
     * @param non-empty-string $finishReason
     */
    public function __construct(
        public Content $content,
        public string $finishReason,
    ) {
    }

    public function getOutput(): ?string
    {
        return trim(implode('', array_map(fn ($p) => $p->text, $this->content->parts))) ?: null;
    }
}
