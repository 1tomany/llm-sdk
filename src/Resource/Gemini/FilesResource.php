<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\File\DeleteFileRequest;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\File\File;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

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
    private const string UPLOAD_COMMAND_UPLOAD = 'upload';
    private const string UPLOAD_COMMAND_FINALIZE = 'upload, finalize';

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     *
     * @throws RuntimeException when opening the file fails
     * @throws RuntimeException when generating a signed URL fails
     * @throws RuntimeException when uploading a file chunk fails
     */
    public function upload(UploadFileRequest $request): UploadFileResponse
    {
        // Ensure the file can be opened
        $fileHandle = $request->openFile();

        try {
            $url = $this->buildUrl(sprintf('upload/%s/files', $this->getApiVersion()));

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
        } catch (LlmSdkExceptionInterface $e) {
            throw new RuntimeException(sprintf('Uploading the file "%s" failed because the server failed to generate the signed upload URL: %s.', $request->getName(), rtrim($e->getMessage(), '.')), $e->getCode(), $e);
        } finally {
            $response = null;
        }

        // Set the chunk size to the amount preferred by the server
        if (is_numeric($headers[self::HEADER_CHUNK_SIZE][0] ?? null)) {
            $uploadChunkSize = (int) $headers[self::HEADER_CHUNK_SIZE][0];
        }

        // Use the default chunk size (8MB) if the server did not return one
        $uploadChunkSize = max(1, $uploadChunkSize ?? self::DEFAULT_CHUNK_SIZE);

        try {
            $uploadOffset = 0;

            /** @var non-empty-string $uploadUrl */
            $uploadUrl = $headers[self::HEADER_UPLOAD_URL][0];

            while ($fileChunk = fread($fileHandle, $uploadChunkSize)) {
                $chunkSize = strlen($fileChunk);

                // Determine the upload command to send
                $uploadCommand = self::UPLOAD_COMMAND_UPLOAD;

                if ($uploadOffset + $chunkSize >= $request->getSize()) {
                    $uploadCommand = self::UPLOAD_COMMAND_FINALIZE;
                }

                $response = $this->doRequest('POST', $uploadUrl, [
                    'headers' => $this->buildHeaders([
                        'content-length' => $request->getSize(),
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ]),
                    'body' => $fileChunk,
                ]);

                // Account for unevenly divided file sizes
                $uploadOffset = $uploadOffset + $chunkSize;
            }
        } catch (LlmSdkExceptionInterface $e) {
            throw new RuntimeException(sprintf('The file "%s" was rejected at byte %d of %d by the server: %s.', $request->getName(), $uploadOffset, $request->getSize(), rtrim($e->getMessage(), '.')), $e->getCode(), $e);
        }

        if (null === $response || empty($response->getContent())) {
            throw new RuntimeException(sprintf('Uploading the file "%s" failed because the server returned an empty response.', $request->getName()));
        }

        $object = $this->doDenormalize($response->toArray(), File::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[file]',
        ]);

        return new UploadFileResponse($request->getVendor(), $object->uri, $object->name, null, $object->expirationTime);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteFileRequest $request): DeleteFileResponse
    {
        $this->doDeleteRequest($request->getUri(), [
            'headers' => $this->buildHeaders(),
        ]);

        return new DeleteFileResponse($request->getVendor(), $request->getUri());
    }
}
