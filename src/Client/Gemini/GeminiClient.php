<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Response\Error\ErrorResponse;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Transfer\Input\File\UploadFileInput;
use OneToMany\LlmSdk\Transfer\Output\File\FileOutput;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpClientResponseInterface;

final readonly class GeminiClient implements FileClientInterface
{
    /**
     * @param non-empty-string $geminiApiKey
     */
    public function __construct(
        #[\SensitiveParameter] private string $geminiApiKey,
        private HttpClientInterface $httpClient,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     */
    public function uploadFile(UploadFileInput $uploadFileInput): FileOutput
    {
        try {
            // @see https://ai.google.dev/gemini-api/docs/files
            $response = $this->httpClient->request('POST', '/upload/v1beta/files', [
                'headers' => [
                    'x-goog-upload-command' => 'start',
                    'x-goog-upload-protocol' => 'resumable',
                    'x-goog-upload-header-content-length' => $uploadFileInput->size,
                    'x-goog-upload-header-content-type' => $uploadFileInput->format,
                ],
                'json' => [
                    'file' => [
                        'displayName' => $uploadFileInput->name,
                    ],
                ],
            ]);

            if (200 !== $response->getStatusCode()) {
                $this->handleResponseError($response);
            }

            $headers = $response->getHeaders(false);

            if (empty($uploadUrl = $headers['x-goog-upload-url'][0] ?? null)) {
                throw new RuntimeException('The resumable URL was not sent with the response.');
            }
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException('Generating the resumable upload URL failed.', previous: $e);
        }

        if (!$fileHandle = @fopen($uploadFileInput->path, 'r')) {
            throw new RuntimeException(sprintf('Opening the file "%s" failed.', $uploadFileInput->name));
        }

        $uploadOffset = 0;

        // Set the chunk size to the amount preferred by the server
        if (is_numeric($headers['x-goog-upload-chunk-granularity'][0] ?? null)) {
            $uploadChunkSize = (int) $headers['x-goog-upload-chunk-granularity'][0];
        }

        // Use the default chunk size (8MB) if the server didn't indicate one
        $uploadChunkSize = max(1, $uploadChunkSize ?? (8 * 1024 * 1024));

        try {
            while ($fileChunk = fread($fileHandle, $uploadChunkSize)) {
                $uploadChunkSize = strlen($fileChunk);

                // Determine the upload command to send
                $uploadCommand = ($uploadOffset + $uploadChunkSize >= $uploadFileInput->size) ? 'upload, finalize' : 'upload';

                // Upload the chunk to the server
                $response = $this->httpClient->request('POST', $uploadUrl, [
                    'headers' => [
                        'content-length' => $uploadChunkSize,
                        'x-goog-api-key' => $this->geminiApiKey,
                        'x-goog-upload-offset' => $uploadOffset,
                        'x-goog-upload-command' => $uploadCommand,
                    ],
                    'body' => $fileChunk,
                ]);

                if (200 !== $response->getStatusCode()) {
                    $this->handleResponseError($response);
                }

                // Account for unevenly divided file sizes
                $uploadOffset = $uploadOffset + $uploadChunkSize;
            }

            $fileResponse = $this->denormalizer->denormalize($response->toArray(), FileResponse::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[file]',
            ]);
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException('The server returned an invalid response.', previous: $e);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException('Decoding the response failed.', previous: $e);
        } finally {
            @fclose($fileHandle);
        }

        return new FileOutput($uploadFileInput->vendor, $fileResponse->toRecord());
    }

    private function handleResponseError(HttpClientResponseInterface $response): never
    {
        try {
            $error = $this->denormalizer->denormalize($response->toArray(false), ErrorResponse::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
            ]);
        } catch (HttpClientExceptionInterface|SerializerExceptionInterface $e) {
            $error = new ErrorResponse($response->getStatusCode(), $response->getContent(false) ?: $e->getMessage());
        }

        throw new RuntimeException($error->message, $response->getStatusCode());
    }
}
