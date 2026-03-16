<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Embedding\Embedding;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
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

            $content = $this->doPostRequest($url, [
                'auth_bearer' => $this->getApiKey(),
                'json' => [
                    ...$request->getRequest(),
                ],
            ]);

            $embedding = $this->doDenormalize($content, Embedding::class, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[data][0]',
            ]);
        } finally {
            $timer->stop();
        }

        return new CreateEmbeddingResponse($request->getModel(), $embedding->embedding, $timer->getDuration());
    }
}
