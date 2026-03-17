<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

use function assert;
use function count;
use function random_int;

final readonly class EmbeddingsResource extends BaseResource implements EmbeddingsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface
     */
    public function create(CreateEmbeddingRequest $request): CreateEmbeddingResponse
    {
        $embedding = [];

        /** @var positive-int $embeddingDimensions */
        $embeddingDimensions = $request->getRequest()['dimensions'] ?? $request->getModel()->getDefaultDimensions();

        for ($i = 0; $i < $embeddingDimensions; ++$i) {
            $embedding[] = $this->faker->randomFloat();
        }

        assert(count($embedding) === $embeddingDimensions);

        return new CreateEmbeddingResponse($request->getModel(), $embedding, random_int(100, 10000));
    }
}
