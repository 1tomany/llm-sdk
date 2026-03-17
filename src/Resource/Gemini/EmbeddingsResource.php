<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\Embedding\Embedding;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
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
                'headers' => $this->buildHeaders(),
                'json' => [
                    ...$request->getRequest(),
                ],
            ]);

            $object = $this->doDenormalize($response, Embedding::class, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[embedding]',
            ]);
        } finally {
            $timer->stop();
        }

        return new CreateEmbeddingResponse($request->getModel(), $object->values, $timer->getDuration());
    }
}
