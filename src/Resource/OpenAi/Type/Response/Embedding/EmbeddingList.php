<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Embedding;

use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Usage\Usage;

final readonly class EmbeddingList
{
    /**
     * @param 'list' $object
     * @param non-empty-list<Embedding> $data
     * @param non-empty-lowercase-string $model
     */
    public function __construct(
        public string $object,
        public array $data,
        public string $model,
        public Usage $usage = new Usage(),
    ) {
    }
}
