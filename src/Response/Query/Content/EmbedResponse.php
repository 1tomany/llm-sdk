<?php

namespace OneToMany\LlmSdk\Response\Query\Content;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;
use OneToMany\LlmSdk\Response\Query\Usage\UsageResponse;

use function array_map;
use function array_sum;
use function sqrt;

final readonly class EmbedResponse extends ExecuteResponse
{
    private float $l2Norm;

    /**
     * @param non-empty-list<float> $embedding
     */
    public function __construct(
        Model $model,
        private array $embedding,
        int|float $runtime = 0,
        UsageResponse $usage = new UsageResponse(),
    ) {
        parent::__construct($model, $runtime, $usage);

        $this->l2Norm = $this->calculateL2Norm();
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
        return $this->l2Norm;
    }

    private function calculateL2Norm(): float
    {
        return sqrt(array_sum(array_map(fn ($x) => $x * $x, $this->embedding)));
    }
}
