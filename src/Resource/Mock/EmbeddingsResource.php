<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

use function assert;
use function count;
use function random_int;

final readonly class EmbeddingsResource implements EmbeddingsResourceInterface
{
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function create(CreateEmbeddingRequest $request): CreateEmbeddingResponse
    {
        $embedding = [];

        for ($i = 0; $i < $request->getDimensions(); ++$i) {
            $embedding[] = $this->faker->randomFloat();
        }

        assert(count($embedding) === $request->getDimensions());

        return new CreateEmbeddingResponse($request->getModel(), $embedding, random_int(100, 10000));
    }
}
