<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Batch;

use OneToMany\LlmSdk\Resource\Gemini\Type\Batch\Enum\State;

final readonly class Metadata
{
    /**
     * @param non-empty-lowercase-string $model
     * @param ?non-empty-string $displayName
     * @param non-empty-string $name
     */
    public function __construct(
        public string $model,
        public ?string $displayName,
        public InputConfig $inputConfig,
        public \DateTimeImmutable $createTime,
        public \DateTimeImmutable $updateTime,
        public BatchStats $batchStats,
        public State $state,
        public string $name,
    ) {
    }
}
