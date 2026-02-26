<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Type\Batch\Batch;
use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

final readonly class BatchClient extends BaseClient implements BatchClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->generateUrl($request->getModel(), 'batchGenerateContent');

        try {
            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'x-goog-api-key' => $this->getApiKey(),
                ],
                'json' => [
                    'batch' => [
                        'displayName' => $request->getName(),
                        'inputConfig' => [
                            'fileName' => $request->getFileName(),
                        ],
                    ],
                ],
            ]);

            $batch = $this->denormalizer->denormalize($response->toArray(true), Batch::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[metadata]',
            ]);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new CreateResponse(
            $request->getModel(),
            $batch->name,
            null,
            $batch->state->isSucceeded(),
            $batch->state->isFailed(),
            $batch->state->isCancelled(),
            $batch->state->isExpired(),
        );
    }
}
