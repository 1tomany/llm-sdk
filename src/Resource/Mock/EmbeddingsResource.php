<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;

use function assert;
use function count;
use function json_encode;
use function random_int;
use function strlen;

final readonly class EmbeddingsResource extends BaseResource implements EmbeddingsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface
     */
    public function create(ProcessQueryRequest $request): CreateEmbeddingResponse
    {
        $embedding = [];

        /** @var positive-int $embeddingDimensions */
        $embeddingDimensions = $request->getRequest()['dimensions'] ?? $request->getModel()->getDefaultDimensions();

        for ($i = 0; $i < $embeddingDimensions; ++$i) {
            $embedding[] = $this->faker->randomFloat(8, -1, 1);
        }

        assert(count($embedding) === $embeddingDimensions);

        return new CreateEmbeddingResponse($request->getModel(), $this->generateId('embedding'), $embedding, null, random_int(100, 10000), new TokenUsage(strlen(json_encode($request->getRequest()) ?: '')));
    }
}
