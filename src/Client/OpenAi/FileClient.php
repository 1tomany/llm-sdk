<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Client\OpenAi\Type\File\DeletedFile;
use OneToMany\AI\Client\OpenAi\Type\File\Enum\Purpose;
use OneToMany\AI\Client\OpenAi\Type\File\File;
use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Request\File\DeleteRequest;
use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\DeleteResponse;
use OneToMany\AI\Response\File\UploadResponse;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

final readonly class FileClient extends OpenAiClient implements FileClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\FileClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $url = $this->generateUrl('files');

        try {
            $purpose = Purpose::create($request->getPurpose());

            $response = $this->httpClient->request('POST', $url, [
                'auth_bearer' => $this->getApiKey(),
                'body' => [
                    'purpose' => $purpose->getValue(),
                    'file' => $request->openFileHandle(),
                ],
            ]);

            $file = $this->serializer->denormalize($response->toArray(true), File::class);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new UploadResponse($request->getModel(), $file->id, $file->filename, $file->purpose->getValue(), $file->getExpiresAt());
    }

    /**
     * @see OneToMany\AI\Contract\Client\FileClientInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $url = $this->generateUrl('files', $request->getUri());

        try {
            $response = $this->httpClient->request('DELETE', $url, [
                'auth_bearer' => $this->getApiKey(),
            ]);

            $deletedFile = $this->serializer->denormalize($response->toArray(true), DeletedFile::class);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new DeleteResponse($request->getModel(), $deletedFile->id);
    }
}
