<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Type\Error\Error;
use OneToMany\LlmSdk\Client\Trait\HttpRequestTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource
{
    use HttpRequestTrait;

    public function __construct(
        protected DenormalizerInterface $denormalizer,
        protected HttpClientInterface $httpClient,
        protected string $apiKey,
        protected string $apiVersion,
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Client\Trait\HttpRequestTrait
     *
     * @return array<mixed>
     */
    protected function buildAuthOptions(): array
    {
        return [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
        ];
    }

    /**
     * @see OneToMany\LlmSdk\Client\Trait\HttpRequestTrait
     *
     * @param array<mixed> $content
     */
    protected function extractErrorMessage(array $content): string
    {
        $error = $this->denormalize($content, Error::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        ]);

        return $error->getMessage();
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
