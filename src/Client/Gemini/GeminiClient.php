<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Exception\HttpTransportException;
use OneToMany\LlmSdk\Client\Gemini\Request\File\UploadFileRequest;
use OneToMany\LlmSdk\Client\Gemini\Response\Error\ErrorResponse;
use OneToMany\LlmSdk\Client\Gemini\Response\File\UploadFileResponse;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Contract\Client\Request\ClientRequestInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Transfer\Input\File\UploadFileInput;
use OneToMany\LlmSdk\Transfer\Output\File\FileOutput;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\HttpExceptionInterface as HttpClientHttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpClientResponseInterface;

use function array_merge;
use function fread;
use function implode;
use function is_numeric;
use function is_string;
use function ltrim;
use function max;
use function rtrim;
use function strlen;
use function trim;

final readonly class GeminiClient implements FileClientInterface
{
    public const string BASE_URL = 'https://generativelanguage.googleapis.com';

    /**
     * @param non-empty-string $apiKey
     */
    public function __construct(
        #[\SensitiveParameter] private string $apiKey,
        private HttpClientInterface $httpClient,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     */
    public function uploadFile(UploadFileInput $uploadFileInput): FileOutput
    {
        $uploadFileRequest = UploadFileRequest::create(...[
            'uploadFileInput' => $uploadFileInput,
        ]);

        $url = $this->buildUrl($uploadFileRequest->url);

        // @see https://ai.google.dev/gemini-api/docs/files
        $response = $this->httpClient->request('POST', $url, [
            'headers' => $uploadFileRequest->getHeaders() + [
                'x-goog-api-key' => $this->apiKey,
            ],
            'json' => $uploadFileRequest->getJson(),
        ]);

        try {
            if (200 !== $response->getStatusCode()) {
                $this->handleResponseError($response);
            }

            $headers = $response->getHeaders(false);
        } catch (HttpClientExceptionInterface $e) {
            throw new HttpTransportException($response, $e);
        }

        if (empty($uploadUrl = $headers['x-goog-upload-url'][0] ?? null)) {
            throw new RuntimeException(\sprintf('The %s server did not include a resumable upload URL in the response headers.', $uploadFileInput->vendor->getName()));
        }

        if (!$fileHandle = @fopen($uploadFileInput->path, 'r')) {
            throw new RuntimeException(\sprintf('Uploading the file "%s" to %s failed because it could not be opened.', $uploadFileInput->name, $uploadFileInput->vendor->getName()));
        }

        $uploadOffset = 0;

        // Set the chunk size to the amount preferred by the server
        if (is_numeric($headers['x-goog-upload-chunk-granularity'][0] ?? null)) {
            $uploadChunkSize = (int) $headers['x-goog-upload-chunk-granularity'][0];
        }

        // Use the default chunk size (8MB) if the server didn't indicate one
        $uploadChunkSize = max(1, $uploadChunkSize ?? (8 * 1024 * 1024));

        try {
            // $response = null;

            while ($fileChunk = fread($fileHandle, $uploadChunkSize)) {
                $uploadChunkSize = strlen($fileChunk);

                // Determine the upload command to send
                $uploadCommand = ($uploadOffset + $uploadChunkSize >= $uploadFileInput->size) ? 'upload, finalize' : 'upload';

                // Upload the chunk to the server
                $response = $this->httpClient->request('POST', $uploadUrl, [
                    'headers' => [
                        'content-length' => $uploadChunkSize,
                        'x-goog-api-key' => $this->apiKey,
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
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException('The server returned an invalid response.', previous: $e);
        }

        if (!$response instanceof HttpClientResponseInterface) {
            throw new RuntimeException(\sprintf('Uploading the file "%s" to %s failed because the server failed to return a response.', $uploadFileInput->name, $uploadFileInput->vendor->getName()));
        }

        try {
            $uploadFileResponse = $this->denormalizer->denormalize($response->toArray(), UploadFileResponse::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[file]',
            ]);
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException('Decoding the response failed.', previous: $e);
        } finally {
            @fclose($fileHandle);
        }

        return new FileOutput($uploadFileInput->vendor, $uploadFileResponse->toRecord());
    }

    /**
     * @return non-empty-string
     */
    private function buildUrl(string ...$paths): string
    {
        return sprintf('%s/%s', rtrim(self::BASE_URL, '/'), ltrim(implode('/', $paths), '/'));
    }

    /**
     * @throws RuntimeException when the server returns an error response
     * @throws RuntimeException when the request was otherwise unsuccessful
     */
    private function doRequest(ClientRequestInterface $request): HttpClientResponseInterface
    {
        $url = $this->buildUrl($request->url);

        $response = $this->httpClient->request($request->method->value, $url, [
            'headers' => array_merge($request->getHeaders(), [
                'x-goog-api-key' => $this->apiKey,
            ]),
            'body' => $request->getBody(),
            'json' => $request->getJson(),
        ]);

        try {
            if ($response->getStatusCode() >= 300) {
                $this->handleResponseError($response);
            }
        } catch (HttpClientExceptionInterface $e) {
            $url = $response->getInfo('url');

            if (!is_string($url) || !$url) {
                $url = $request->url;
            }

            if ($e instanceof HttpClientHttpExceptionInterface) {
                $statusCode = $e->getResponse()->getStatusCode();
            }

            throw new RuntimeException(\sprintf('Request to "%s" failed.', $url), $statusCode ?? $e->getCode(), $e);
        }

        return $response;
    }

    private function handleResponseError(HttpClientResponseInterface $response): never
    {
        try {
            $error = $this->denormalizer->denormalize($response->toArray(false), ErrorResponse::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
            ]);
        } catch (HttpClientExceptionInterface|SerializerExceptionInterface $e) {
            $error = new ErrorResponse($response->getStatusCode(), trim($response->getContent(false)) ?: $e->getMessage());
        }

        throw new RuntimeException($error->message, $response->getStatusCode(), previous: $e ?? null);
    }
}
