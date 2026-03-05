<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

use OneToMany\LlmSdk\Client\OpenAI\Type\Error\Error;
use OneToMany\LlmSdk\Client\Trait\DenormalizerTrait;
use OneToMany\LlmSdk\Client\Trait\SupportsModelTrait;
use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function array_merge;
use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseClient
{
    use DenormalizerTrait;
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
     * @param array<string, mixed> $options
     *
     * @return array<mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        $options = array_merge($options, [
            'auth_bearer' => $this->apiKey,
        ]);

        try {
            $response = $this->httpClient->request($method, $url, $options);

            /** @var int<100, 599> $statusCode */
            $statusCode = $response->getStatusCode();

            /** @var array<mixed> $content */
            $content = $response->toArray(false);

            if ($statusCode >= 300 || isset($content['error'])) {
                $error = $this->denormalize($content, Error::class, [
                    UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
                ]);

                throw new RuntimeException($error->getMessage(), $statusCode);
            }
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $content;
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
}
