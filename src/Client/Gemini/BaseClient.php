<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Type\Error\Error;
use OneToMany\LlmSdk\Client\Trait\DenormalizeTrait;
use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseClient
{
    use DenormalizeTrait;

    public function __construct(
        protected DenormalizerInterface $denormalizer,
        protected HttpClientInterface $httpClient,
        #[\SensitiveParameter] protected string $apiKey,
        protected string $apiVersion,
    ) {
    }

    /**
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        $options = array_merge_recursive($options, [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
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
     * @return non-empty-string
     */
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('https://generativelanguage.googleapis.com/%s', ltrim(implode('/', $paths), '/'));
    }

    /**
     * @return non-empty-string
     */
    protected function generateModelUrl(string $model, string $action): string
    {
        return $this->generateUrl($this->apiVersion, 'models', sprintf('%s:%s', $model, $action));
    }
}
