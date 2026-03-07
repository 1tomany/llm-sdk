<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\File\File;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

use function ceil;
use function fread;
use function sprintf;
use function strlen;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     *
     * @throws RuntimeException when an empty file is uploaded
     * @throws RuntimeException when a signed URL is not generated
     * @throws RuntimeException when uploading a file chunk fails
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        // Ensure the file can be opened
        $fileHandle = $request->openFile();

        try {
            // Generate a signed URL to upload the file with
            $url = $this->buildUrl('upload', $this->apiVersion, 'files');

            $response = $this->httpClient->request('POST', $url, [
                'headers' => $this->buildHttpHeaders([
                    'x-goog-upload-command' => 'start',
                    'x-goog-upload-protocol' => 'resumable',
                    'x-goog-upload-header-content-length' => $request->getSize(),
                    'x-goog-upload-header-content-type' => $request->getFormat(),
                ]),
                'json' => [
                    'file' => [
                        'displayName' => $request->getName(),
                    ],
                ],
            ]);

            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException('Generating the signed upload URL failed.', $response->getStatusCode());
            }

            $headers = $response->getHeaders(true);

            /** @var non-empty-string $uploadUrl */
            $uploadUrl = $headers['x-goog-upload-url'][0];

            /** @var positive-int $uploadChunkSize */
            $uploadChunkSize = (int) $headers['x-goog-upload-chunk-granularity'][0];

            // Calculate the number of chunks needed to upload the file
            $uploadChunkCount = (int) ceil($request->getSize() / $uploadChunkSize);

            // Counters to track progress
            $uploadChunk = $uploadOffset = 0;

            while ($fileChunk = fread($fileHandle, $uploadChunkSize)) {
                // Determine the command to let the server know if we're done uploading or not
                $uploadCommand = (++$uploadChunk >= $uploadChunkCount) ? 'upload, finalize' : 'upload';

                $response = $this->httpClient->request('POST', $uploadUrl, [
                    'headers' => $this->buildHttpHeaders([
                        'content-length' => $request->getSize(),
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ]),
                    'body' => $fileChunk,
                ]);

                if (200 !== $response->getStatusCode()) {
                    throw new RuntimeException(sprintf('Chunk %d of %d was rejected by the server.', $uploadChunk, $uploadChunkCount), $response->getStatusCode());
                }

                $uploadOffset = $uploadOffset + strlen($fileChunk);
            }

            $file = $this->doDeserialize($response->getContent(), File::class, context: [
                UnwrappingDenormalizer::UNWRAP_PATH => '[file]',
            ]);
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return new UploadResponse($request->getModel(), $file->uri, $file->name, null, $file->expirationTime);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $this->doDeleteRequest($request->getUri(), [
            'headers' => $this->buildHttpHeaders(),
        ]);

        return new DeleteResponse($request->getModel(), $request->getUri());
    }
}
