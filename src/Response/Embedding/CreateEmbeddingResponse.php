<?php

namespace OneToMany\LlmSdk\Response\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Response\BaseResponse;

use function max;

final readonly class CreateEmbeddingResponse extends BaseResponse
{
    /**
     * @param list<float> $embedding
     */
    public function __construct(
        Model $model,
        private array $embedding,
        private int|float $runtime = 0,
    ) {
        parent::__construct($model);
    }

    /**
     * @return list<float>
     */
    public function getEmbedding(): array
    {
        return $this->embedding;
    }

    /**
     * @return non-negative-int
     */
    public function getRuntime(): int
    {
        return max(0, (int) $this->runtime);
    }
}
