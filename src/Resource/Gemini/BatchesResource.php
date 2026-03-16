<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Batch\Batch;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

use function sprintf;

final readonly class BatchesResource extends BaseResource implements BatchesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateRequest $request): CreateBatchResponse
    {
        $url = $this->buildUrl($this->getApiVersion(), sprintf('models/%s:%s', $request->getModel()->getId(), $request->getModel()->isEmbedding() ? 'asyncBatchEmbedContent' : 'batchGenerateContent'));

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

        $object = $this->doDenormalize($content, Batch::class);

        return new CreateBatchResponse($request->getModel(), $object->name, $object->metadata->state->getValue());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadRequest $request): ReadBatchResponse
    {
        $url = $this->buildUrl($this->getApiVersion(), $request->getUri());

        $content = $this->doGetRequest($url, [
            'headers' => $this->buildHeaders(),
        ]);

        $batch = $this->doDenormalize($content, Batch::class);

        return new ReadBatchResponse($request->getModel(), $batch->name, $batch->metadata->state->getValue());
    }
}
