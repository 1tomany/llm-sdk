<?php

namespace OneToMany\LlmSdk\Client\Gemini;

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

use function sprintf;

abstract readonly class GeminiClient
{
    use HttpExceptionTrait;
    use SupportsModelTrait;

    public const string BASE_URI = 'https://generativelanguage.googleapis.com';

    /**
     * @param non-empty-string $apiKey
     */
    public function __construct(
        protected DenormalizerInterface $denormalizer,
        protected HttpClientInterface $httpClient,
        #[\SensitiveParameter] protected string $apiKey,
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
     * @see OneToMany\LlmSdk\Contract\Client\ClientInterface
     *
     * @return non-empty-list<non-empty-lowercase-string>
     */
    public function getSupportedModels(): array
    {
        return [
            'gemini-3-pro-preview',
            'gemini-3-flash-preview',
            'gemini-2.5-pro',
            'gemini-2.5-flash',
            'gemini-2.5-flash-preview-09-2025',
            'gemini-2.5-flash-lite',
            'gemini-2.5-flash-lite-preview-09-2025',
        ];
    }

    /**
     * @param non-empty-lowercase-string $model
     * @param non-empty-string $action
     *
     * @return non-empty-string
     */
    protected function generateUrl(string $model, string $action): string
    {
        return sprintf('%s/v1beta/models/%s:%s', self::BASE_URI, $model, $action);
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
