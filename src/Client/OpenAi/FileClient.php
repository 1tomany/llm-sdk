<?php

namespace OneToMany\AI\Clients\Client\OpenAI;

use OneToMany\AI\Clients\Client\OpenAI\Type\File\DeletedFile;
use OneToMany\AI\Clients\Client\OpenAI\Type\File\Enum\Purpose;
use OneToMany\AI\Clients\Client\OpenAI\Type\File\File;
use OneToMany\AI\Clients\Contract\Client\FileClientInterface;
use OneToMany\AI\Clients\Request\File\DeleteRequest;
use OneToMany\AI\Clients\Request\File\UploadRequest;
use OneToMany\AI\Clients\Response\File\DeleteResponse;
use OneToMany\AI\Clients\Response\File\UploadResponse;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

final readonly class FileClient extends BaseClient implements FileClientInterface
{
    /**
     * @see OneToMany\AI\Clients\Contract\Client\FileClientInterface
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

            $file = $this->denormalizer->denormalize($response->toArray(true), File::class);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new UploadResponse($request->getModel(), $file->id, $file->filename, $file->purpose->getValue(), $file->getExpiresAt());
    }

    /**
     * @see OneToMany\AI\Clients\Contract\Client\FileClientInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $url = $this->generateUrl('files', $request->getUri());

        try {
            $response = $this->httpClient->request('DELETE', $url, [
                'auth_bearer' => $this->getApiKey(),
            ]);

            $deletedFile = $this->denormalizer->denormalize($response->toArray(true), DeletedFile::class);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new DeleteResponse($request->getModel(), $deletedFile->id);
    }
}
