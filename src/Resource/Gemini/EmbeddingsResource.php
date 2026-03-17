<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface;
use OneToMany\LlmSdk\Request\Embedding\CreateEmbeddingRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\Embedding\Embedding;
use OneToMany\LlmSdk\Response\Embedding\CreateEmbeddingResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Stopwatch\Stopwatch;

use function sprintf;

final readonly class EmbeddingsResource extends BaseResource implements EmbeddingsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\EmbeddingsResourceInterface
     */
    public function create(CreateEmbeddingRequest $request): CreateEmbeddingResponse
    {
        $timer = new Stopwatch(true)->start('create');

        try {
            $url = $this->buildUrl($this->getApiVersion(), sprintf('models/%s:embedContent', $request->getModel()->getId()));

            $response = $this->doPostRequest($url, [
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
