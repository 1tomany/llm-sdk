<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Type\Batch\Batch;
use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

final readonly class BatchClient extends BaseClient implements BatchClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->generateModelUrl($request->getModel(), 'batchGenerateContent');

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

        $batch = $this->denormalize($data, Batch::class);

        return new CreateResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        $content = $this->doRequest('GET', $this->generateUrl($this->apiVersion, $request->getUri()));

        $batch = $this->denormalize($content, Batch::class);

        return new ReadResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }
}
