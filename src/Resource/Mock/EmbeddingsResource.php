<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;

use function is_int;
use function max;
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

        if (isset($request->getRequest()['dimensions'])) {
            $dimensions = $request->getRequest()['dimensions'];
        }

        $dimensions = !is_int($dimensions ?? null) ? 1024 : max(1, $dimensions);

        for ($i = 0; $i < $dimensions; ++$i) {
            $embedding[] = $this->faker->randomFloat();
        }

        return new CreateEmbeddingResponse($request->getModel(), $embedding, random_int(100, 10000));
    }
}
