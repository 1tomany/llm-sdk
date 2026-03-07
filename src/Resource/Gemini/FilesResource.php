<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
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
use function is_numeric;
use function rtrim;
use function sprintf;
use function strlen;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    private const int DEFAULT_CHUNK_SIZE = 8 * 1024 * 1024;

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     *
     * @throws RuntimeException when opening the file fails
     * @throws InvalidArgumentException when an empty file is uploaded
     * @throws InvalidArgumentException when a file without a format is uploaded
     * @throws RuntimeException when a signed URL is not generated
     * @throws RuntimeException when uploading a file chunk fails
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $url = $this->buildUrl('upload', $this->apiVersion, 'files');

        try {
            $fileHandle = $request->openFile();

            if (!$fileSize = $request->getSize()) {
                throw new InvalidArgumentException('Empty files cannot be uploaded.');
            }

            if (!$fileFormat = $request->getFormat()) {
                throw new InvalidArgumentException('The file format cannot be empty.');
            }

            $uploadCommand = 'start';
            $uploadProtocol = 'resumable';

            $response = $this->doRequest('POST', $url, [
                'headers' => $this->buildHeaders([
                    'x-goog-upload-command' => $uploadCommand,
                    'x-goog-upload-protocol' => $uploadProtocol,
                    'x-goog-upload-header-content-length' => $fileSize,
                    'x-goog-upload-header-content-type' => $fileFormat,
                ]),
                'json' => [
                    'file' => [
                        'displayName' => $request->getName(),
                    ],
                ],
            ]);

            $headers = $response->getHeaders(false);

            if (empty($headers['x-guploader-uploadid'][0] ?? null)) {
                throw new RuntimeException('The header "x-guploader-uploadid" was not sent in the response.');
            }
        } catch (ExceptionInterface $e) {
            throw new RuntimeException(sprintf('Generating the signed upload URL failed: %s.', rtrim($e->getMessage(), '.')), $e->getCode(), $e);
        }

        // The default chunk size is 8MB
        // $uploadChunkSize = 8 * 1024 * 1024;

        // Adjust the chunk size to the size preferred by the server
        if (is_numeric($headers['x-goog-upload-chunk-granularity'][0] ?? null)) {
            $uploadChunkSize = (int) $headers['x-goog-upload-chunk-granularity'][0];
        }

        $uploadChunkSize = (($uploadChunkSize ?? 0) <= 0) ? self::DEFAULT_CHUNK_SIZE : $uploadChunkSize;

        // if (($uploadChunkSize ?? 0) < 1) {
        //     $uploadChunkSize = self::DEFAULT_CHUNK_SIZE;
        // }

        try {
            // Calculate the number of chunks needed to upload the file
            $uploadChunkCount = (int) ceil($request->getSize() / $uploadChunkSize);

            // Counters to track progress
            $uploadChunk = $uploadOffset = 0;

            while ($fileChunk = fread($fileHandle, $uploadChunkSize)) {
                // Determine the command to let the server know if we're done uploading or not
                $uploadCommand = (++$uploadChunk >= $uploadChunkCount) ? 'upload, finalize' : 'upload';

                $response = $this->doRequest('POST', $url, [
                    'headers' => $this->buildHeaders([
                        'content-length' => $request->getSize(),
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ]),
                    'query' => [
                        'upload_id' => $headers['x-guploader-uploadid'][0],
                        'upload_protocol' => $uploadProtocol,
                    ],
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
            'headers' => $this->buildHeaders(),
        ]);

        return new DeleteResponse($request->getModel(), $request->getUri());
    }
}
