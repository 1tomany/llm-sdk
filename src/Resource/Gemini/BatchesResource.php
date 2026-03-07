<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Resource\Gemini\Type\Batch\Batch;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

final readonly class BatchesResource extends BaseResource implements BatchesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->generateModelUrl($request->getModel(), 'batchGenerateContent');

        $content = $this->doHttpPostRequest($url, [
            'headers' => $this->buildHttpHeaders(),
            'json' => [
                'batch' => [
                    'displayName' => $request->getName(),
                    'inputConfig' => [
                        'fileName' => $request->getFileName(),
                    ],
                ],
            ],
        ]);

        $batch = $this->doDeserialize($content, Batch::class);

        return new CreateResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        $content = $this->doHttpRequest('GET', $this->generateUrl($this->apiVersion, $request->getUri()));

        $batch = $this->doDeserialize($content, Batch::class);

        return new ReadResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }
}
