<?php

namespace OneToMany\LlmSdk\Response\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Response\BaseResponse;
use OneToMany\LlmSdk\Response\Output\Usage\TokenUsage;

use function array_map;
use function array_sum;
use function count;
use function max;
use function sqrt;

final readonly class CreateEmbeddingResponse extends BaseResponse
{
    /**
     * @param non-empty-list<float> $embedding
     */
    public function __construct(
        Model $model,
        private array $embedding,
        private int|float $runtime = 0,
        private TokenUsage $usage = new TokenUsage(),
    ) {
        if ([] === $embedding) {
            throw new InvalidArgumentException('The embedding vector cannot be empty.');
        }

        parent::__construct($model);
    }

    /**
     * @return positive-int
     */
    public function getDimensions(): int
    {
        return count($this->embedding);
    }

    /**
     * @return non-empty-list<float>
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

    public function getUsage(): TokenUsage
    {
        return $this->usage;
    }
}
