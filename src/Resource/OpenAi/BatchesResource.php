<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Client\OpenAi\Type\Batch\Batch;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

final readonly class BatchesResource extends CommonResource implements BatchesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->generateUrl('batches');

        $content = $this->doRequest('POST', $url, [
            'json' => [
                'endpoint' => $request->getEndpoint(),
                'input_file_id' => $request->getFileUri(),
                'completion_window' => $request->getTimeframe(),
            ],
        ]);

        $batch = $this->denormalize($content, Batch::class);

        return new CreateResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        $batch = $this->denormalize($this->doRequest('GET', $this->generateUrl('batches', $request->getUri())), Batch::class);

        return new ReadResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }
}
