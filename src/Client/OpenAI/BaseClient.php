<?php

namespace OneToMany\AI\Clients\Client\OpenAI;

use OneToMany\AI\Clients\Client\OpenAI\Type\Error\Error;
use OneToMany\AI\Clients\Client\Trait\HttpExceptionTrait;
use OneToMany\AI\Clients\Client\Trait\SupportsModelTrait;
use OneToMany\AI\Clients\Contract\Client\Type\Error\ErrorInterface;
use OneToMany\AI\Clients\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseClient
{
    use HttpExceptionTrait;
    use SupportsModelTrait;

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
     * @see OneToMany\AI\Clients\Contract\Client\ClientInterface
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
        return sprintf('https://api.openai.com/v1/%s', ltrim(implode('/', $paths), '/'));
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
