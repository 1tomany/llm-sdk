<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Embedding\EmbeddingList;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;
use Symfony\Component\Stopwatch\Stopwatch;

final readonly class EmbeddingsResource extends BaseResource implements EmbeddingsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface
     */
    public function create(ProcessQueryRequest $request): CreateEmbeddingResponse
    {
        $timer = new Stopwatch(true)->start('create');

        try {
            $response = $this->doPostRequest($request->getUrl(), [
                'auth_bearer' => $this->getApiKey(),
                'json' => [
                    ...$request->getRequest(),
                ],
            ]);

            $object = $this->doDenormalize($response, EmbeddingList::class);
        } finally {
            $timer->stop();
        }

        return new CreateEmbeddingResponse($request->getModel(), null, $object->data[0]->embedding, null, $timer->getDuration(), new TokenUsage($object->usage->getInputTokens()));
    }
}
