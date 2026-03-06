<?php

namespace OneToMany\LlmSdk\Client\OpenAi;

use OneToMany\LlmSdk\Client\OpenAi\Type\Error\Error;
use OneToMany\LlmSdk\Client\Trait\DenormalizeTrait;
use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function implode;
use function is_array;
use function is_string;
use function ltrim;
use function sprintf;

abstract readonly class BaseClient
{
    use DenormalizeTrait;

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
     * @param array<string, mixed> $options
     *
     * @return array<mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        try {
            $response = $this->httpClient->request($method, $url, $options + [
                'auth_bearer' => $this->apiKey,
            ]);

            /** @var int<100, 599> $statusCode */
            $statusCode = $response->getStatusCode();

            /** @var array<mixed> $content */
            $content = $response->toArray(false);

            if ($statusCode >= 300) {
                if (is_array($content['error'] ?? null)) {
                    if (is_string($content['error']['message'] ?? null)) {
                        throw new RuntimeException($content['error']['message'], $statusCode);
                    }
                }

                throw new RuntimeException('no good happen', $statusCode);
                // $error = $this->denormalize($content, Error::class, [
                //     UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
                // ]);

                // throw new RuntimeException($error->message, $statusCode);
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
        return sprintf('https://api.openai.com/v1/%s', ltrim(implode('/', $paths), '/'));
    }
}
