<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

use OneToMany\LlmSdk\Client\OpenAI\Type\Error\Error;
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

    public const string BASE_URI = 'https://api.openai.com/v1';

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
            'gpt-5.2-pro',
            'gpt-5.2-pro-2025-12-11',
            'gpt-5.2',
            'gpt-5.2-2025-12-11',
            'gpt-5.1',
            'gpt-5.1-2025-11-13',
            'gpt-5-pro',
            'gpt-5-pro-2025-10-06',
            'gpt-5',
            'gpt-5-2025-08-07',
            'gpt-5-mini',
            'gpt-5-mini-2025-08-07',
            'gpt-5-nano',
            'gpt-5-nano-2025-08-07',
            'gpt-4.1',
            'gpt-4.1-2025-04-14',
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
     * @param array<mixed> $options
     *
     * @return list<array<string, mixed>>|array<string, mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        try {
            $options = array_merge_recursive($options, [
                'auth_bearer' => $this->getApiKey(),
            ]);

            /** @var list<array<string, mixed>>|array<string, mixed> $content */
            $content = $this->httpClient->request($method, $url, $options)->toArray(true);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return $content;
    }

    protected function decodeErrorResponse(ResponseInterface $response): ErrorInterface
    {
        try {
            $error = $this->denormalizer->denormalize($response->toArray(false), Error::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
            ]);
        } catch (HttpClientExceptionInterface $e) {
            $error = new Error($e instanceof HttpClientDecodingExceptionInterface ? $e->getMessage() : $response->getContent(false));
        } catch (SerializerExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $error;
    }
}
