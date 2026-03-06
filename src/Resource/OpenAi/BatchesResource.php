<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Client\OpenAi\Type\Batch\Batch;
use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

use function array_merge;

final readonly class BatchesResource extends BaseResource implements BatchesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->generateUrl('batches');

        $options = [
            'auth_header' => $this->getApiKey(),
        ];

        $content = $this->doHttpPostRequest($url, $options + [
            'json' => array_merge($request->getOptions(), [
                'input_file_id' => $request->getFileUri(),
            ]),
        ]);

        $batch = $this->doDeserialize($content, Batch::class);

        return new CreateResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        $url = $this->generateUrl('batches', $request->getUri());

        $content = $this->doHttpGetRequest($url, [
            'auth_header' => $this->getApiKey(),
        ]);

        $batch = $this->doDeserialize($content, Batch::class);

        return new ReadResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }
}
