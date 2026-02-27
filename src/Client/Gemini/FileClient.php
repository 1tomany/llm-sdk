<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Type\File\File;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

use function ceil;
use function fread;
use function sprintf;
use function strlen;

final readonly class FileClient extends BaseClient implements FileClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     *
     * @throws RuntimeException an empty file is uploaded
     * @throws RuntimeException a signed URL is not generated
     * @throws RuntimeException uploading a file chunk fails
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        // Ensure the file can be opened first
        $fileHandle = $request->openFileHandle();

        if (0 === $fileSize = $request->getSize()) {
            throw new RuntimeException('Empty files cannot be uploaded.');
        }

        try {
            // Generate a signed URL to upload the file with
            $url = $this->generateUrl('upload', $this->apiVersion, 'files');

            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                    'x-goog-upload-command' => 'start',
                    'x-goog-upload-protocol' => 'resumable',
                    'x-goog-upload-header-content-length' => $fileSize,
                    'x-goog-upload-header-content-type' => $request->getFormat(),
                ],
                'json' => [
                    'file' => [
                        'displayName' => $request->getName(),
                    ],
                ],
            ]);

            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException('Generating the signed upload URL failed: %s.', $response->getStatusCode());
            }

            $headers = $response->getHeaders(true);

            /** @var non-empty-string $uploadUrl */
            $uploadUrl = $headers['x-goog-upload-url'][0];

            /** @var positive-int $uploadChunkSize */
            $uploadChunkSize = (int) $headers['x-goog-upload-chunk-granularity'][0];

            // Calculate the number of chunks needed to upload the file
            $uploadChunkCount = (int) ceil($fileSize / $uploadChunkSize);

            // Counters to track progress
            $uploadChunk = $uploadOffset = 0;

            while ($fileChunk = fread($fileHandle, $uploadChunkSize)) {
                // Determine the command to let the server know if we're done uploading or not
                $uploadCommand = (++$uploadChunk >= $uploadChunkCount) ? 'upload, finalize' : 'upload';

                $response = $this->httpClient->request('POST', $uploadUrl, [
                    'headers' => [
                        'content-length' => $fileSize,
                        'x-goog-api-key' => $this->apiKey,
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ],
                    'body' => $fileChunk,
                ]);

                if (200 !== $response->getStatusCode()) {
                    throw new RuntimeException(sprintf('Chunk %d of %d was rejected by the server.', $uploadChunk, $uploadChunkCount), $response->getStatusCode());
                }

                $uploadOffset = $uploadOffset + strlen($fileChunk);
            }

            $file = $this->denormalize($response->toArray(true), File::class, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[file]',
            ]);
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return new UploadResponse($request->getModel(), $file->uri, $file->name, null, $file->expirationTime);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $this->doRequest('DELETE', $request->getUri());

        return new DeleteResponse($request->getModel(), $request->getUri());
    }
}
