<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Client\Trait\DenormalizeTrait;
use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource
{
    use DenormalizeTrait;

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

            print_r($response);
            exit;
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return [];
    }

    /**
     * @return non-empty-string
     */
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('https://api.openai.com/v1/%s', ltrim(implode('/', $paths), '/'));
    }
}
