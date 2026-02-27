<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Exception\DecodingResponseContentFailedException;
use OneToMany\LlmSdk\Client\Gemini\Type\File\File;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

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
        // Files are uploaded in 8MB chunks
        $uploadChunkByteCount = 8 * 1024 * 1024;

        // Ensure the file can be opened and read before
        // doing anything that requires an HTTP request
        $fileHandle = $request->openFileHandle();

        // Calculate the total number of chunks needed to upload the entire file
        $uploadChunkCount = (int) ceil($request->getSize() / $uploadChunkByteCount);

        if (0 === $uploadChunkCount || 0 === $request->getSize()) {
            throw new RuntimeException('Empty files cannot be uploaded.');
        }

        try {
            // Generate a signed URL to upload the file with
            $url = $this->generateUrl('upload', $this->getApiVersion(), 'files');

            $response = $this->httpClient->request('POST', $url, [
                'headers' => [
                    'x-goog-api-key' => $this->getApiKey(),
                    'x-goog-upload-command' => 'start',
                    'x-goog-upload-protocol' => 'resumable',
                    'x-goog-upload-header-content-type' => $request->getFormat(),
                    'x-goog-upload-header-content-length' => $request->getSize(),
                ],
                'json' => [
                    'file' => [
                        'displayName' => $request->getName(),
                    ],
                ],
            ]);

            if (200 !== $response->getStatusCode()) {
                throw new RuntimeException(sprintf('Generating the signed URL failed: %s.', $this->decodeErrorResponse($response)->getInlineMessage()), $response->getStatusCode());
            }

            /** @var non-empty-string $uploadUrl */
            $uploadUrl = $response->getHeaders(true)['x-goog-upload-url'][0];

            // Counters to track progress
            $uploadChunk = $uploadOffset = 0;

            while ($fileChunk = fread($fileHandle, $uploadChunkByteCount)) {
                // Determine the command to let the server know if we're done uploading or not
                $uploadCommand = (++$uploadChunk >= $uploadChunkCount) ? 'upload, finalize' : 'upload';

                $response = $this->httpClient->request('POST', $uploadUrl, [
                    'headers' => [
                        'content-length' => $request->getSize(),
                        'x-goog-api-key' => $this->getApiKey(),
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ],
                    'body' => $fileChunk,
                ]);

                if (200 !== $response->getStatusCode()) {
                    throw new RuntimeException(sprintf('Chunk %d of %d was rejected by the server: %s.', $uploadChunk, $uploadChunkCount, $this->decodeErrorResponse($response)->getInlineMessage()), $response->getStatusCode());
                }

                // Don't assume the chunk was an even 8MB
                $uploadOffset = $uploadOffset + strlen($fileChunk);
            }

            $file = $this->denormalizer->denormalize($response->toArray(true), File::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[file]',
            ]);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        } catch (SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException($request, $e);
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
