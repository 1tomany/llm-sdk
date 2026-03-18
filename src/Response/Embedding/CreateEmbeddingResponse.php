<?php

namespace OneToMany\LlmSdk\Response\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Response\BaseResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;

use function array_key_first;
use function array_key_last;
use function array_map;
use function array_sum;
use function count;
use function max;
use function sprintf;
use function sqrt;

final readonly class CreateEmbeddingResponse extends BaseResponse implements \JsonSerializable, \Stringable
{
    private float $l2Norm;

    /**
     * @param non-empty-list<float> $embedding
     *
     * @throws InvalidArgumentException when the embedding vector is empty
     */
    public function __construct(
        string|Model $model,
        private array $embedding,
        private int|float $runtime = 0,
        private TokenUsage $usage = new TokenUsage(),
    ) {
        parent::__construct($model);

        if ([] === $embedding) {
            throw new InvalidArgumentException('The embedding vector cannot be empty.');
        }

        $this->l2Norm = $this->calculateL2Norm(...[
            'vector' => $this->embedding,
        ]);
    }

    /**
     * @see \Stringable
     *
     * @return non-empty-string
     */
    public function __toString(): string
    {
        return $this->getEmbeddingLabel();
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
     * @return non-empty-string
     */
    public function getEmbeddingLabel(): string
    {
        return sprintf('[%.12f ... %.12f]', $this->embedding[array_key_first($this->embedding)], $this->embedding[array_key_last($this->embedding)]);
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
     * @see \JsonSerializable
     *
     * @return array{
     *   model: non-empty-lowercase-string,
     *   vendor: non-empty-lowercase-string,
     *   dimensions: positive-int,
     *   embedding: non-empty-list<float>,
     *   l2Norm: float,
     *   usage: TokenUsage,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'model' => $this->getModel()->getValue(),
            'vendor' => $this->getVendor()->getValue(),
            'dimensions' => $this->getDimensions(),
            'embedding' => $this->getEmbedding(),
            'l2Norm' => $this->getL2Norm(),
            'usage' => $this->getUsage(),
        ];
    }

    /**
     * @param non-empty-list<float> $vector
     */
    private function calculateL2Norm(array $vector): float
    {
        return sqrt(array_sum(array_map(fn ($e) => $e * $e, $vector)));
    }
}
