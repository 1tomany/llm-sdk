<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateBatchRequest;
use OneToMany\LlmSdk\Request\Batch\ReadBatchRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Request\Batch\CreateBatch;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Batch\Batch;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

final readonly class BatchesResource extends BaseResource implements BatchesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateBatchRequest $request): CreateBatchResponse
    {
        $createBatch = new CreateBatch($request->getModel(), $this->getApiVersion(), $request->getFileUri()->getUri());

        $response = $this->doPostRequest($this->buildUrl('batches'), [
            'auth_header' => $this->getApiKey(),
            'json' => [
                ...$createBatch->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($response, Batch::class);

        return new CreateBatchResponse($request->getModel(), $object->id, $object->status->getValue(), $object->output_file_id);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadBatchRequest $request): ReadBatchResponse
    {
        $url = $this->buildUrl('batches', $request->getUri());

        $response = $this->doGetRequest($url, [
            'auth_header' => $this->getApiKey(),
        ]);

        $object = $this->doDenormalize($response, Batch::class);

        return new ReadBatchResponse($request->getModel(), $object->id, $object->status->getValue(), $object->output_file_id);
    }
}
