<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

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
        $url = $this->generateUrl('batches');

        try {
            $response = $this->httpClient->request('POST', $url, [
                'auth_bearer' => $this->getApiKey(),
                'json' => [
                    'input_file_id' => $request->getFileUri(),
                    'endpoint' => '',
                    'completion_window' => '24h',
                ],
            ]);

            /**
             * @var array<string, mixed> $responseContent
             */
            $responseContent = $response->toArray(true);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        throw new \Exception('Not implemented');
    }
}
