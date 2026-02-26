<?php

namespace OneToMany\LlmSdk\Client\Claude;

use OneToMany\LlmSdk\Client\Gemini\Type\Error\Error;
use OneToMany\LlmSdk\Client\Trait\HttpExceptionTrait;
use OneToMany\LlmSdk\Client\Trait\SupportsModelTrait;
use OneToMany\LlmSdk\Contract\Client\Type\Error\ErrorInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function array_merge_recursive;
use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseClient
{
    use HttpExceptionTrait;
    use SupportsModelTrait;

    public const string BASE_URI = 'https://api.anthropic.com/v1';

    /**
     * @param non-empty-string $apiKey
     * @param non-empty-string $apiVersion
     */
    public function __construct(
        protected DenormalizerInterface $denormalizer,
        protected HttpClientInterface $httpClient,
        #[\SensitiveParameter] protected string $apiKey,
        protected string $apiVersion = '2023-06-01',
    ) {
    }

    /**
     * @return non-empty-string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @return non-empty-string
     */
    public function getApiVersion(): string
    {
        return $this->apiVersion;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     *
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public function getSupportedModels(): array
    {
        return [
            'claude-opus-4-6',
            'claude-sonnet-4-5',
            'claude-sonnet-4-5-20250929',
            'claude-haiku-4-5',
            'claude-haiku-4-5-20251001',
        ];
    }

    /**
     * @param non-empty-string $paths
     *
     * @return non-empty-string
     */
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('%s/%s', self::BASE_URI, ltrim(implode('/', $paths), '/'));
    }

    /**
     * @param 'GET'|'POST'|'PUT'|'DELETE' $method
     * @param non-empty-string $url
     * @param array<mixed> $options
     */
    protected function doRequest(string $method, string $url, array $options): ResponseInterface
    {
        $headers = [
            'headers' => [
                'x-api-key' => $this->getApiKey(),
                'anthropic-version' => $this->getApiVersion(),
            ],
        ];

        return $this->httpClient->request($method, $url, array_merge_recursive($headers, $options));
    }

    protected function decodeErrorResponse(ResponseInterface $response): ErrorInterface
    {
        try {
            $error = $this->denormalizer->denormalize($response->toArray(false), Error::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
            ]);
        } catch (HttpClientExceptionInterface $e) {
            $error = new Error($response->getStatusCode(), $e instanceof HttpClientDecodingExceptionInterface ? $e->getMessage() : $response->getContent(false));
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $error;
    }
}
