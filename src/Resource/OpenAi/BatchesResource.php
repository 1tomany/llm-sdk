<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Batch\Batch;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

final readonly class BatchesResource extends BaseResource implements BatchesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateRequest $request): CreateBatchResponse
    {
        $url = $this->buildUrl('batches');

        $content = $this->doPostRequest($url, [
            'auth_header' => $this->getApiKey(),
            'json' => [
                'endpoint' => $request->getEndpoint(),
                'completion_window' => $request->getWindow(),
                'input_file_id' => $request->getFileUri(),
            ],
        ]);

        $batch = $this->doDenormalize($content, Batch::class);

        return new CreateBatchResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadRequest $request): ReadBatchResponse
    {
        $url = $this->buildUrl('batches', $request->getUri());

        $content = $this->doGetRequest($url, [
            'auth_header' => $this->getApiKey(),
        ]);

        $batch = $this->doDenormalize($content, Batch::class);

        return new ReadBatchResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }
}
