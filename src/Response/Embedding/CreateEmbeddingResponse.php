<?php

namespace OneToMany\LlmSdk\Response\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Response\BaseResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;

use function array_map;
use function array_sum;
use function count;
use function max;
use function sqrt;

final readonly class CreateEmbeddingResponse extends BaseResponse
{
    private float $l2Norm;

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

        $this->l2Norm = $this->calculateL2Norm(...[
            'vector' => $this->embedding,
        ]);

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

    /**
     * @return non-empty-list<float>
     */
    public function getNormalizedEmbedding(): array
    {
        return array_map(fn ($e): float => $e / $this->l2Norm, $this->embedding);
    }

    public function getL2Norm(): float
    {
        return $this->l2Norm;
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

    /**
     * @param non-empty-list<float> $vector
     */
    private function calculateL2Norm(array $vector): float
    {
        return sqrt(array_sum(array_map(fn ($e) => $e * $e, $vector)));
    }
}
