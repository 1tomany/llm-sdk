<?php

namespace OneToMany\LlmSdk\Response\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Response\BaseResponse;

use function array_map;
use function array_sum;
use function max;
use function sqrt;

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

    public function getL2Norm(): float
    {
        return sqrt(array_sum(array_map(fn ($e) => $e * $e, $this->embedding)));
    }

    /**
     * @return non-negative-int
     */
    public function getRuntime(): int
    {
        return max(0, (int) $this->runtime);
    }
}
