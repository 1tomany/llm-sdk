<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\File\File;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

use function ceil;
use function fread;
use function is_numeric;
use function max;
use function rtrim;
use function sprintf;
use function strlen;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    private const int DEFAULT_CHUNK_SIZE = 8 * 1024 * 1024;

    private const string HEADER_CHUNK_SIZE = 'x-goog-upload-chunk-granularity';
    private const string HEADER_UPLOAD_URL = 'x-goog-upload-url';

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     *
     * @throws RuntimeException when opening the file fails
     * @throws RuntimeException when a signed URL is not generated
     * @throws RuntimeException when uploading a file chunk fails
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $fileHandle = $request->openFile();

        try {
            $url = $this->buildUrl('upload', $this->apiVersion, 'files');

            $response = $this->doRequest('POST', $url, [
                'headers' => $this->buildHeaders([
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

            $headers = $response->getHeaders(false);

            if (empty($headers[self::HEADER_UPLOAD_URL][0] ?? null)) {
                throw new RuntimeException(sprintf('The header "%s" was not sent in the response.', self::HEADER_UPLOAD_URL));
            }

            /** @var non-empty-string $uploadUrl */
            $uploadUrl = $headers[self::HEADER_UPLOAD_URL][0];
        } catch (ExceptionInterface $e) {
            throw new RuntimeException(sprintf('Generating the signed upload URL failed: %s.', rtrim($e->getMessage(), '.')), $e->getCode(), $e);
        }

        // The default chunk size is 8MB
        $uploadChunkSize = self::DEFAULT_CHUNK_SIZE;

        // Adjust the chunk size to the size preferred by the server
        if (is_numeric($headers[self::HEADER_CHUNK_SIZE][0] ?? null)) {
            $uploadChunkSize = max(1, (int) $headers[self::HEADER_CHUNK_SIZE][0]);
        }

        // Calculate the number of chunks needed to upload the file
        $uploadChunkCount = (int) ceil($request->getSize() / $uploadChunkSize);

        try {
            // Counters to track progress
            $uploadChunk = $uploadOffset = 0;

            while ($fileChunk = fread($fileHandle, $uploadChunkSize)) {
                // Determine the command to let the server know if we're done uploading or not
                $uploadCommand = (++$uploadChunk >= $uploadChunkCount) ? 'upload, finalize' : 'upload';

                $response = $this->doRequest('POST', $uploadUrl, [
                    'headers' => $this->buildHeaders([
                        'content-length' => $request->getSize(),
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ]),
                    'body' => $fileChunk,
                ]);

                $uploadOffset = $uploadOffset + strlen($fileChunk);
            }

            $file = $this->doDeserialize($response->getContent(), File::class, context: [
                UnwrappingDenormalizer::UNWRAP_PATH => '[file]',
            ]);
        } catch (ExceptionInterface $e) {
            throw new RuntimeException(sprintf('Chunk %d of %d was rejected by the server: %s.', $uploadChunk, $uploadChunkCount, rtrim($e->getMessage(), '.')), $e->getCode(), $e);
        }

        return new UploadResponse($request->getModel(), $file->uri, $file->name, null, $file->expirationTime);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $this->doDeleteRequest($request->getUri(), [
            'headers' => $this->buildHeaders(),
        ]);

        return new DeleteResponse($request->getModel(), $request->getUri());
    }
}
