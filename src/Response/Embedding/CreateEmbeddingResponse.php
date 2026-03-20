<?php

namespace OneToMany\LlmSdk\Response\Embedding;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Response\Query\QueryResponseInterface;
use OneToMany\LlmSdk\Contract\Response\Usage\TokenUsageInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Response\BaseResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;

use function array_map;
use function array_sum;
use function count;
use function max;
use function sqrt;

final readonly class CreateEmbeddingResponse extends BaseResponse implements \JsonSerializable, QueryResponseInterface
{
    private float $l2Norm;

    /**
     * @param non-empty-list<float> $embedding
     *
     * @throws InvalidArgumentException when the embedding vector is empty
     */
    public function __construct(
        string|Model $model,
        private ?string $uri,
        private array $embedding,
        private ?string $error = null,
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
     * @see OneToMany\LlmSdk\Contract\Response\Query\QueryResponseInterface
     *
     * @return non-empty-list<float>
     */
    public function __invoke(): array
    {
        return $this->getEmbedding();
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Response\Query\QueryResponseInterface
     */
    public function getUri(): ?string
    {
        return $this->uri;
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
     * @see OneToMany\LlmSdk\Contract\Response\Query\QueryResponseInterface
     */
    public function getOutput(): ?string
    {
        return \json_encode($this->getEmbedding());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Response\Query\QueryResponseInterface
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Response\Query\QueryResponseInterface
     */
    public function getRuntime(): int
    {
        return max(0, (int) $this->runtime);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Response\Query\QueryResponseInterface
     */
    public function getUsage(): TokenUsageInterface
    {
        return $this->usage;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   model: non-empty-lowercase-string,
     *   vendor: non-empty-lowercase-string,
     *   uri: ?non-empty-string,
     *   dimensions: positive-int,
     *   embedding: non-empty-list<float>,
     *   l2Norm: float,
     *   runtime: non-negative-int,
     *   usage: TokenUsageInterface,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'model' => $this->getModel()->getValue(),
            'vendor' => $this->getVendor()->getValue(),
            'uri' => $this->getUri(),
            'dimensions' => $this->getDimensions(),
            'embedding' => $this->getEmbedding(),
            'l2Norm' => $this->getL2Norm(),
            'runtime' => $this->getRuntime(),
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
