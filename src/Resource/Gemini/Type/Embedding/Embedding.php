<?php

namespace OneToMany\LlmSdk\Resource\Gemini\Type\Embedding;

final readonly class Embedding
{
    /**
     * @param non-empty-list<float> $values
     */
    public function __construct(public array $values)
    {
    }

    /**
     * @return array{
     *   values: non-empty-list<float>,
     * }
     */
    public function toArray(): array
    {
        return [
            'values' => $this->values,
        ];
    }
}
