<?php

namespace OneToMany\LlmSdk\Response\Query\Content;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;
use OneToMany\LlmSdk\Response\Query\UsageResponse;

final readonly class EmbedResponse extends ExecuteResponse
{
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
    }

    /**
     * @return non-empty-list<float>
     */
    public function getEmbedding(): array
    {
        return $this->embedding;
    }
}
