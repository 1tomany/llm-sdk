<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

use OneToMany\LlmSdk\Client\OpenAI\Type\Batch\Batch;
use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

final readonly class BatchClient extends BaseClient implements BatchClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->generateUrl('batches');

        try {
            $requestContent = [
                'completion_window' => '24h',
                'endpoint' => '/v1/responses',
            ];

            $response = $this->httpClient->request('POST', $url, [
                'auth_bearer' => $this->getApiKey(),
                'json' => $requestContent + [
                    'input_file_id' => $request->getFileUri(),
                ],
            ]);

            $batch = $this->denormalizer->denormalize($response->toArray(true), Batch::class);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new CreateResponse(
            $request->getModel(),
            $batch->id,
            $batch->output_file_id,
            $batch->status->isCompleted(),
            $batch->status->isFailed(),
            $batch->status->isCancelled(),
            $batch->status->isExpired(),
        );
    }
}
