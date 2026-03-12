<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Batch\Batch;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

final readonly class BatchesResource extends BaseResource implements BatchesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->buildModelUrl($request->getModel(), 'batchGenerateContent');

        $content = $this->doPostRequest($url, [
            'headers' => $this->buildHeaders(),
            'json' => [
                'batch' => [
                    'displayName' => $request->getName(),
                    'inputConfig' => [
                        'fileName' => $request->getFileName(),
                    ],
                ],
            ],
        ]);

        $batch = $this->doDenormalize($content, Batch::class);

        return new CreateResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        $url = $this->buildUrl($this->apiVersion, $request->getUri());

        $content = $this->doGetRequest($url, [
            'headers' => $this->buildHeaders(),
        ]);

        $batch = $this->doDenormalize($content, Batch::class);

        return new ReadResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }
}
