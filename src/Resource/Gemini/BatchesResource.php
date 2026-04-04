<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateBatchRequest;
use OneToMany\LlmSdk\Request\Batch\ReadBatchRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Batch\CreateBatch;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\Batch\Batch;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

use function sprintf;

final readonly class BatchesResource extends BaseResource implements BatchesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateBatchRequest $request): CreateBatchResponse
    {
        $createBatch = new CreateBatch(
            $request->getName(),
            $request->getFileUri()->getUri(),
        );

        $url = $this->buildUrl($this->getApiVersion(), sprintf('models/%s:%s', $request->getModel()->getId(), $request->getModel()->isEmbedding() ? 'asyncBatchEmbedContent' : 'batchGenerateContent'));

        $response = $this->doPostRequest($url, [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$createBatch->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($response, Batch::class);

        return new CreateBatchResponse($request->getModel(), $object->name, $object->metadata->state->getValue());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadBatchRequest $request): ReadBatchResponse
    {
        $url = $this->buildUrl($this->getApiVersion(), $request->getUri());

        $response = $this->doGetRequest($url, [
            'headers' => $this->buildHeaders(),
        ]);

        $object = $this->doDenormalize($response, Batch::class);

        return new ReadBatchResponse($request->getModel(), $object->name, $object->metadata->state->getValue());
    }
}
