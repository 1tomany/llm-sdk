<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Embedding\EmbeddingList;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;
use Symfony\Component\Stopwatch\Stopwatch;

final readonly class EmbeddingsResource extends BaseResource implements EmbeddingsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface
     */
    public function create(CreateEmbeddingRequest $request): CreateEmbeddingResponse
    {
        $timer = new Stopwatch(true)->start('create');

        try {
            $url = $this->buildUrl('embeddings');

            $response = $this->doPostRequest($url, [
                'auth_bearer' => $this->getApiKey(),
                'json' => [
                    ...$request->getRequest(),
                ],
            ]);

            $object = $this->doDenormalize($response, EmbeddingList::class);
        } finally {
            $timer->stop();
        }

        return new CreateEmbeddingResponse($request->getModel(), $object->data[0]->embedding, $timer->getDuration(), new TokenUsage($object->usage->getInputTokens()));
    }
}
