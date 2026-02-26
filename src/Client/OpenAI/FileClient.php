<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

use OneToMany\LlmSdk\Client\Exception\DecodingResponseContentFailedException;
use OneToMany\LlmSdk\Client\OpenAI\Type\File\DeletedFile;
use OneToMany\LlmSdk\Client\OpenAI\Type\File\Enum\Purpose;
use OneToMany\LlmSdk\Client\OpenAI\Type\File\File;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

final readonly class FileClient extends BaseClient implements FileClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $url = $this->generateUrl('files');

        try {
            $purpose = Purpose::create($request->getPurpose());

            $data = $this->doRequest('POST', $url, [
                'body' => [
                    'purpose' => $purpose->getValue(),
                    'file' => $request->openFileHandle(),
                ],
            ]);

            $file = $this->denormalizer->denormalize($data, File::class);
        } catch (SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException($request, $e);
        }

        return new UploadResponse($request->getModel(), $file->id, $file->filename, $file->purpose->getValue(), $file->getExpiresAt());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $data = $this->doRequest('DELETE', $this->generateUrl('files', $request->getUri()));

        try {
            $deletedFile = $this->denormalizer->denormalize($data, DeletedFile::class);
        } catch (SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException($request, $e);
        }

        return new DeleteResponse($request->getModel(), $deletedFile->id);
    }
}
