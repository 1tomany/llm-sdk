<?php

namespace OneToMany\LlmSdk\Response\Query\Content;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;
use OneToMany\LlmSdk\Response\Query\Usage\UsageResponse;

use function array_map;
use function array_sum;
use function count;
use function sqrt;

final readonly class EmbedResponse extends ExecuteResponse
{
    /**
     * @var non-empty-list<float>
     */
    private array $embedding;

    /**
     * @var positive-int
     */
    private int $dimensions;
    private float $l2Norm;

    /**
     * @var non-empty-list<float>
     */
    private array $embeddingL2Norm;

    /**
     * @param list<float> $embedding
     */
    public function __construct(
        Model $model,
        array $embedding,
        int|float $runtime = 0,
        UsageResponse $usage = new UsageResponse(),
    ) {
        parent::__construct($model, $runtime, $usage);

        if ([] === $embedding) {
            throw new InvalidArgumentException('The embedding vector cannot be empty.');
        }
        $this->embedding = $embedding;

        $this->dimensions = count($this->embedding);

        $this->l2Norm = $this->calculateL2Norm(...[
            'vector' => $this->embedding,
        ]);

        // Normalize the embedding vector
        $mapper = function (float $e): float {
            return $e / $this->l2Norm;
        };

        $this->embeddingL2Norm = array_map($mapper, $embedding);
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

    /**
     * @return non-empty-list<float>
     */
    public function getEmbeddingL2Norm(): array
    {
        return $this->embeddingL2Norm;
    }

    /**
     * @return positive-int
     */
    public function getDimensions(): int
    {
        return $this->dimensions;
    }

    /**
     * @param non-empty-list<float> $vector
     */
    private function calculateL2Norm(array $vector): float
    {
        return sqrt(array_sum(array_map(fn ($e) => $e * $e, $vector)));
    }
}
