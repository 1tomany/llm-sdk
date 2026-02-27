<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

use OneToMany\LlmSdk\Client\OpenAI\Type\Batch\Batch;
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
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        $content = $this->doRequest('GET', $this->generateUrl('batches', $request->getUri()));

        $batch = $this->denormalize($content, Batch::class);

        return new ReadResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }
}
