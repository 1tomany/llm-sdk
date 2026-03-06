<?php

namespace OneToMany\LlmSdk\Client\Anthropic;

use OneToMany\LlmSdk\Client\Anthropic\Type\Error\Error;
use OneToMany\LlmSdk\Client\Trait\DenormalizerTrait;
use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function array_merge_recursive;
use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseClient
{
    use DenormalizerTrait;

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
                'x-api-key' => $this->apiKey,
                'anthropic-version' => $this->apiVersion,
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

                throw new RuntimeException($error->message, $statusCode);
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
        return sprintf('https://api.anthropic.com/v1/%s', ltrim(implode('/', $paths), '/'));
    }
}
