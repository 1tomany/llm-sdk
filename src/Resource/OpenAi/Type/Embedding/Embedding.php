<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Embedding;

final readonly class Embedding
{
    /**
     * @param 'embedding' $object
     * @param non-negative-int $index
     * @param non-empty-list<float> $embedding
     */
    public function __construct(
        public string $object,
        public int $index,
        public array $embedding,
    ) {
    }
}
