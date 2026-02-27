<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Exception\DecodingResponseContentFailedException;
use OneToMany\LlmSdk\Client\Gemini\Type\Batch\Batch;
use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

final readonly class BatchClient extends BaseClient implements BatchClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->generateModelUrl($request->getModel(), 'batchGenerateContent');

        try {
            $data = $this->doRequest('POST', $url, [
                'json' => [
                    'batch' => [
                        'displayName' => $request->getName(),
                        'inputConfig' => [
                            'fileName' => $request->getFileName(),
                        ],
                    ],
                ],
            ]);

            $batch = $this->denormalizer->denormalize($data, Batch::class);
        } catch (SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException($request, $e);
        }

        return new CreateResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        $url = $this->generateUrl($this->getApiVersion(), $request->getUri());

        try {
            $batch = $this->denormalizer->denormalize($this->doRequest('GET', $url), Batch::class);
        } catch (SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException($request, $e);
        }

        return new ReadResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }
}
