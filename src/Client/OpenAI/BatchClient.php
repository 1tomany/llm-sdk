<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

use OneToMany\LlmSdk\Client\Exception\DecodingResponseContentFailedException;
use OneToMany\LlmSdk\Client\OpenAI\Type\Batch\Batch;
use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

final readonly class BatchClient extends BaseClient implements BatchClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        $url = $this->generateUrl('batches');

        try {
            $data = $this->doRequest('POST', $url, [
                'json' => [
                    'endpoint' => $request->getEndpoint(),
                    'input_file_id' => $request->getFileUri(),
                    'completion_window' => $request->getTimeframe(),
                ],
            ]);

            $batch = $this->denormalizer->denormalize($data, Batch::class);
        } catch (SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException($request->getRequestType(), $request->getModel(), $e);
        }

        return new CreateResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        $url = $this->generateUrl('batches', $request->getUri());

        try {
            $batch = $this->denormalizer->denormalize($this->doRequest('GET', $url), Batch::class);
        } catch (SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException('batch.read', $request->getModel(), $e);
        }

        return new ReadResponse($request->getModel(), $batch->id, $batch->status->getValue(), $batch->output_file_id);
    }
}
